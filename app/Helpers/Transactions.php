<?php

namespace App\Helpers;

use App\Commission;
use App\Loan;
use App\Setting;

class Transactions
{
    /**
     * Settings instance.
     *
     * @var \App\Setting
     */
    protected $settings;

    /**
     * Comission instance.
     *
     * @var \App\Comission
     */
    protected $comissions;

    /**
     * Set daily rate for anual fee.
     *
     * @var float
     */
    protected $daily_rate;

    /**
     * Create instance controller..
     *
     * @param \App\Setting    $settings
     * @param \App\Commission $commissions
     */
    public function __construct(Setting $settings, Commission $commissions)
    {
        $this->settings    = $settings;
        $this->commissions = $commissions;
        $this->daily_rate  = ($this->settings->value('TASA_ANUAL') / 365) / 100;
    }

    /**
     * Amortization Calculation.
     *
     * @param float  $amount
     * @param string $type
     * @param float  $commission
     */
    public function amortization($amount, $type = 'Q', $commission)
    {
        $equivalent_rate      = $this->checkEquivalentRate($type);
        $periods              = 'Q' == $type ? 4 : 1;
        $fee_comission        = round($commission / $periods, 4);
        $equivalent_comission = 'Q' === $type ? round($fee_comission / 4, 2) : round($fee_comission / 30, 2);
        $quote                = $this->pmt($equivalent_rate, $periods, (float) $amount);
        $total_quote          = $quote + $fee_comission;
        $total_days           = $this->checkTotalDays($type);

        $table      = [
            [
                'fee'            => $total_quote,
                'interest'       => 0,
                'commission'     => $fee_comission,
                'capital'        => 0,
                'balance'        => (float) $amount,
                'balance_total'  => (float) $amount + $commission,
                'payday_carbon'  => now(),
                'payday'         => now(),
            ],
        ];

        for ($i=1; $i <= $periods; ++$i) {
            $payday                      = $table[$i - 1]['payday_carbon'];
            $table[$i]['fee']            = round($total_quote, 4);
            $in                          = $equivalent_rate * $table[$i - 1]['balance'];
            $table[$i]['interest']       = round($in, 4);
            $table[$i]['capital']        = round($total_quote - $in - $table[0]['commission'], 4);
            $table[$i]['commission']     = $table[0]['commission'];
            $table[$i]['balance']        = round($table[$i - 1]['balance'] - $table[$i]['capital'], 4);
            $table[$i]['balance_total']  = round($table[$i - 1]['balance_total'] - $table[$i]['capital'] - $table[0]['commission'], 4);
            $table[$i]['payday_carbon']  = $payday;
            $payday                      = $payday->addDays('Q' == $type ? 7 : 30);
            $table[$i]['payday']         = $payday->format('Y-m-d');
        }

        unset($table[0]);
        $global = [];
        if ('Q' == $type) {
            $global = $this->simpleGlobalAmortization(collect($table)->sum('fee'), $total_quote, $total_days, $fee_comission, $type);
        }

        return [
            'amortization' => $table,
            'global'       => $global,
        ];
    }

    public function simpleGlobalAmortization($amount, $total_quote, $total_days, $comission, $type = 'Q')
    {
        $table = [
            [
                'day'                   => 0,
                'pay'                   => 0,
                'balance'               => $amount,
                'balance_total'         => $amount,
                'commission'            => 0,
                'interest'              => 0,
                'comission_prepayment'  => 0,
            ],
        ];
        $count        = 0;
        $global_count = 0;
        $stop_days    = 'Q' == $type ? 7 : 30;

        for ($i = 1; $i <= $total_days + 1; ++$i) {
            $table[$i]['day']                  = $i;
            $table[$i]['commission']           = 0;
            $table[$i]['comission_prepayment'] = 0;
            $table[$i]['interest']             = 0;
            $table[$i]['balance']              = $table[$i - 1]['balance'];
            $table[$i]['balance_total']        = $table[$i - 1]['balance_total'];

            ++$count;
            if ($stop_days == $count) {
                $table[$i]['pay']               = round($total_quote, 2);
                $table[$i]['balance']           = $table[$i - 1]['balance'];
                $table[$i]['balance_total']     = $table[$i]['balance'];
            }
            if ($count > $stop_days) {
                $table[$i]['balance']           = round($table[$i - 1]['balance'] - round($total_quote, 2), 2);
                $table[$i]['balance_total']     = $table[$i]['balance'];
                $count                          = 1;
            }
        }

        $table[$total_days + 1]['balance']       = 0;
        $table[$total_days + 1]['balance_total'] = 0;

        return $table;
    }

    // public function global_amortization($amount, $total_quote, $total_days, $daily_rate, $comission, $type = 'Q')
    // {
    //     $table = [
    //         [
    //             'day'        => 0,
    //             'pay'        => 0,
    //             'balance'    => $amount,
    //             'interest'   => 0,
    //             'commission' => $comission,
    //         ],
    //     ];
    //     $count     = 0;
    //     $stop_days = 'Q' == $type ? 7 : 30;

    //     $commission_rate = round($comission / 4, 2);
    //     for ($i=1; $i < $total_days + 1; ++$i) {
    //         $table[$i]['day']        = $i;
    //         $table[$i]['balance']    = $table[$i - 1]['balance'];
    //         $table[$i]['interest']   = round($table[$i - 1]['balance'] * $daily_rate, 2);

    //         $table[$i]['commission'] = $table[$i - 1]['commission'];
    //         ++$count;
    //         if ($stop_days == $count) {
    //             $collection              = collect($table);
    //             $sum_interest            = round($collection->reverse()->take(6)->sum('interest'), 2);
    //             $table[$i]['pay']        = round($total_quote, 2);
    //             $table[$i]['balance']    = round($table[$i - 1]['balance'] - (round($total_quote, 2) - $commission_rate - ($sum_interest + $table[$i - 1]['interest'])), 2);
    //             $table[$i]['interest']   = round($table[$i - 1]['balance'] * $daily_rate, 2);
    //             $table[$i]['commission'] = $table[$i - 1]['commission'] - $commission_rate;
    //             $count                   = 0;
    //         }
    //     }
    //     $table[0]['comission_prepayment'] = round(collect($table)->sum('interest'), 2);
    //     $table[0]['balance_total']        = $table[0]['balance'] + $table[0]['interest'] + $table[0]['commission'] + $table[0]['comission_prepayment'];

    //     for ($i = 1; $i < $total_days + 1; ++$i) {
    //         $table[$i]['comission_prepayment'] = $table[$i - 1]['comission_prepayment'];
    //         $table[$i]['balance_total']        = $table[$i]['balance'] + $table[$i]['commission'] + $table[$i]['comission_prepayment'];

    //         ++$count;

    //         if ($stop_days == $count) {
    //             $collection                        = collect($table);
    //             $sum_interest                      = round($collection->reverse()->take($total_days - $i)->sum('interest'), 2);
    //             $table[$i]['comission_prepayment'] = round($sum_interest, 2);
    //             $table[$i]['balance_total']        = $table[$i]['pay'] + $table[$i]['balance'] + $table[$i]['commission'] + round($sum_interest, 2);
    //             $count                             = 0;
    //         }
    //     }

    //     return $table;
    // }

    public function debt2($amortization, $total_days, $quote)
    {
        $late = [
            [
                'min'        => 1,
                'max'        => 7,
                'percentage' => 2.5,
                'amount'     => round($amortization[0]['balance'] * (2.5 / 100), 2),
            ],
            [
                'min'        => 8,
                'max'        => 30,
                'percentage' => 5,
                'amount'     => round($amortization[0]['balance'] * (5 / 100), 2),
            ],
            [
                'min'        => 31,
                'max'        => 9999,
                'percentage' => 15,
                'amount'     => round($amortization[0]['balance'] * (15 / 100), 2),
            ],
        ];

        $table = [
            [
                'balance'             => $amortization[0]['balance'],
                'debt_days'           => 0,
                'charge_late_payment' => 0,
                'debt_total'          => 0,
                'pay_debt'            => $amortization[0]['balance_total'],
            ],
        ];
        $count         = 0;
        $count_cycle   = 0;
        $balance_total = $amortization[0]['balance_total'];
        for ($i = 1; $i < $total_days + 1; ++$i) {
            $table[$i]['balance']             = $table[$i - 1]['balance'];
            $table[$i]['charge_late_payment'] = $table[$i - 1]['charge_late_payment'];
            $table[$i]['debt_total']          = $table[$i - 1]['debt_total'];
            $table[$i]['pay_debt']            = $table[$i - 1]['pay_debt'];
            if ($table[$i - 1]['debt_days'] > 0) {
                $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
            } else {
                $table[$i]['debt_days'] = $table[$i - 1]['debt_days'];
            }
            ++$count;
            if (8 == $count && 0 == $count_cycle) {
                ++$count_cycle;
                $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
                if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] < 7) {
                    $table[$i]['charge_late_payment'] = $late[0]['amount'];
                } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] < 30) {
                    $table[$i]['charge_late_payment'] = $late[1]['amount'];
                } elseif ($table[$i]['debt_days'] >= 31) {
                    $table[$i]['charge_late_payment'] = $late[2]['amount'];
                }
                $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
                $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
                $count                   = 0;
            } elseif (6 == $count && $count_cycle >= 1) {
                ++$count_cycle;
                $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
                if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] <= 7) {
                    $table[$i]['charge_late_payment'] = $late[0]['amount'];
                } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] <= 30) {
                    $table[$i]['charge_late_payment'] = $late[1]['amount'];
                } elseif ($table[$i]['debt_days'] >= 31) {
                    $table[$i]['charge_late_payment'] = $late[2]['amount'];
                }
                $table[$i]['pay_debt']   = $balance_total;
                $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
                --$count_cycle;
            } elseif (7 == $count && $count_cycle >= 1) {
                ++$count_cycle;
                $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
                if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] <= 7) {
                    $table[$i]['charge_late_payment'] = $late[0]['amount'];
                } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] <= 30) {
                    $table[$i]['charge_late_payment'] = $late[1]['amount'];
                } elseif ($table[$i]['debt_days'] >= 31) {
                    $table[$i]['charge_late_payment'] = $late[2]['amount'];
                }
                $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
                $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
                $count                   = 0;
            }
        }

        return $table;
    }

    public function debt($balance, $balance_total, $total_days, $quote, Loan $loan)
    {
        $late = [
            [
                'min'        => 1,
                'max'        => 7,
                'percentage' => 2.5,
                'amount'     => round($quote * (2.5 / 100), 2),
            ],
            [
                'min'        => 8,
                'max'        => 30,
                'percentage' => 5,
                'amount'     => round($quote * (5 / 100), 2),
            ],
            [
                'min'        => 31,
                'max'        => 9999,
                'percentage' => 15,
                'amount'     => round($quote * (15 / 100), 2),
            ],
        ];

        $table = [
            [
                'balance'             => $balance,
                'debt_days'           => 0,
                'charge_late_payment' => 0,
                'debt_total'          => 0,
                'pay_debt'            => $balance_total,
            ],
        ];
        $global = $loan->global;

        $count               = 0;
        $count_cycle         = 0;
        $charge_late_payment = 0;
        $total_debt          = 0;
        $index               = 0;
        $balance_total_debt  = 0;

        for ($i = 1; $i < $total_days + 1; ++$i) {
            if ($global[$i]['pay']) {
                $day                 = $global[$i]['day'];
                if ($day >= 1 && $day <= 7) {
                    $charge_late_payment = $late[0]['amount'];
                    $index               = $global[$i]['day'];
                } elseif ($day >= 8 && $day <= 30) {
                    $charge_late_payment = $late[1]['amount'];
                    $index               = $global[$i]['day'];
                } elseif ($day >= 31) {
                    $charge_late_payment = $late[2]['amount'];
                    $index               = $global[$i]['day'];
                }
                $balance_total_debt += $global[$i]['pay'];

                $total_debt += $charge_late_payment;
            }
        }

        if ($total_days != $index) {
            if ($total_days >= 1 && $total_days <= 7) {
                $total_debt += $late[0]['amount'];
            } elseif ($total_days >= 8 && $total_days <= 30) {
                $total_debt += $late[1]['amount'];
            } elseif ($total_days >= 31) {
                $total_debt += $late[2]['amount'];
            }
            $balance_total_debt += $quote;
        } else {
            $balance_total_debt += $quote;
        }

        $pay_debt   = $balance_total + $total_debt;
        $debt_total = $balance_total_debt + $total_debt;
        // dump("$total_days, $total_debt, $pay_debt, $debt_total");

        // for ($i = 1; $i < $total_days + 1; ++$i) {
        //     $table[$i]['balance']             = $table[$i - 1]['balance'];
        //     $table[$i]['charge_late_payment'] = $table[$i - 1]['charge_late_payment'];
        //     $table[$i]['debt_total']          = $table[$i - 1]['debt_total'];
        //     $table[$i]['pay_debt']            = $table[$i - 1]['pay_debt'];

        //     if ($table[$i - 1]['debt_days'] > 0) {
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //     } else {
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'];
        //     }

        //     ++$count;
        //     if (1 == $count && 0 == $count_cycle) {
        //         ++$count_cycle;
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //         if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] < 7) {
        //             $table[$i]['charge_late_payment'] = $late[0]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] < 30) {
        //             $table[$i]['charge_late_payment'] = $late[1]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 31) {
        //             $table[$i]['charge_late_payment'] = $late[2]['amount'];
        //         }
        //         $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
        //         $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
        //         $count                   = 0;
        //     } elseif (6 == $count && $count_cycle >= 1) {
        //         ++$count_cycle;
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //         if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] <= 7) {
        //             $table[$i]['charge_late_payment'] = $late[0]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] <= 30) {
        //             $table[$i]['charge_late_payment'] = $late[1]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 31) {
        //             $table[$i]['charge_late_payment'] = $late[2]['amount'];
        //         }
        //         $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
        //         $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
        //         --$count_cycle;
        //     } elseif (7 == $count && $count_cycle >= 1) {
        //         ++$count_cycle;
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //         if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] <= 7) {
        //             $table[$i]['charge_late_payment'] = $late[0]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] <= 30) {
        //             $table[$i]['charge_late_payment'] = $late[1]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 31) {
        //             $table[$i]['charge_late_payment'] = $late[2]['amount'];
        //         }
        //         $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
        //         $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
        //         $count                   = 0;
        //     }
        // }

        return [
            'charge_late_payment' => $total_debt, 'pay_debt' =>$pay_debt, 'debt_total'=>$debt_total,
        ];
    }

    public static function calculateDue($balance, $balance_total, $total_days, $quote, Loan $loan)
    {
        $late = [
            [
                'min'        => 1,
                'max'        => 7,
                'percentage' => 2.5,
                'amount'     => round($quote * (2.5 / 100), 2),
            ],
            [
                'min'        => 8,
                'max'        => 30,
                'percentage' => 5,
                'amount'     => round($quote * (5 / 100), 2),
            ],
            [
                'min'        => 31,
                'max'        => 9999,
                'percentage' => 15,
                'amount'     => round($quote * (15 / 100), 2),
            ],
        ];

        $table = [
            [
                'balance'             => $balance,
                'debt_days'           => 0,
                'charge_late_payment' => 0,
                'debt_total'          => 0,
                'pay_debt'            => $balance_total,
            ],
        ];
        $global = $loan->global;

        $count               = 0;
        $count_cycle         = 0;
        $charge_late_payment = 0;
        $total_debt          = 0;
        $index               = 0;
        $balance_total_debt  = 0;

        for ($i = 1; $i < $total_days + 1; ++$i) {
            if ($global[$i]['pay']) {
                $day                 = $global[$i]['day'];
                if ($day >= 1 && $day <= 7) {
                    $charge_late_payment = $late[0]['amount'];
                    $index               = $global[$i]['day'];
                } elseif ($day >= 8 && $day <= 30) {
                    $charge_late_payment = $late[1]['amount'];
                    $index               = $global[$i]['day'];
                } elseif ($day >= 31) {
                    $charge_late_payment = $late[2]['amount'];
                    $index               = $global[$i]['day'];
                }
                $balance_total_debt += $global[$i]['pay'];

                $total_debt += $charge_late_payment;
            }
        }

        if ($total_days != $index) {
            if ($total_days >= 1 && $total_days <= 7) {
                $total_debt += $late[0]['amount'];
            } elseif ($total_days >= 8 && $total_days <= 30) {
                $total_debt += $late[1]['amount'];
            } elseif ($total_days >= 31) {
                $total_debt += $late[2]['amount'];
            }
            $balance_total_debt += $quote;
        } else {
            $balance_total_debt += $quote;
        }

        $pay_debt   = $balance_total + $total_debt;
        $debt_total = $balance_total_debt + $total_debt;
        // dump("$total_days, $total_debt, $pay_debt, $debt_total");

        // for ($i = 1; $i < $total_days + 1; ++$i) {
        //     $table[$i]['balance']             = $table[$i - 1]['balance'];
        //     $table[$i]['charge_late_payment'] = $table[$i - 1]['charge_late_payment'];
        //     $table[$i]['debt_total']          = $table[$i - 1]['debt_total'];
        //     $table[$i]['pay_debt']            = $table[$i - 1]['pay_debt'];

        //     if ($table[$i - 1]['debt_days'] > 0) {
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //     } else {
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'];
        //     }

        //     ++$count;
        //     if (1 == $count && 0 == $count_cycle) {
        //         ++$count_cycle;
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //         if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] < 7) {
        //             $table[$i]['charge_late_payment'] = $late[0]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] < 30) {
        //             $table[$i]['charge_late_payment'] = $late[1]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 31) {
        //             $table[$i]['charge_late_payment'] = $late[2]['amount'];
        //         }
        //         $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
        //         $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
        //         $count                   = 0;
        //     } elseif (6 == $count && $count_cycle >= 1) {
        //         ++$count_cycle;
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //         if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] <= 7) {
        //             $table[$i]['charge_late_payment'] = $late[0]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] <= 30) {
        //             $table[$i]['charge_late_payment'] = $late[1]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 31) {
        //             $table[$i]['charge_late_payment'] = $late[2]['amount'];
        //         }
        //         $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
        //         $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
        //         --$count_cycle;
        //     } elseif (7 == $count && $count_cycle >= 1) {
        //         ++$count_cycle;
        //         $table[$i]['debt_days'] = $table[$i - 1]['debt_days'] + 1;
        //         if ($table[$i]['debt_days'] >= 1 && $table[$i]['debt_days'] <= 7) {
        //             $table[$i]['charge_late_payment'] = $late[0]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 8 && $table[$i]['debt_days'] <= 30) {
        //             $table[$i]['charge_late_payment'] = $late[1]['amount'];
        //         } elseif ($table[$i]['debt_days'] >= 31) {
        //             $table[$i]['charge_late_payment'] = $late[2]['amount'];
        //         }
        //         $table[$i]['pay_debt']   = $balance_total + $table[$i]['charge_late_payment'];
        //         $table[$i]['debt_total'] = round(($quote * $count_cycle) + $table[$i]['charge_late_payment'], 2);
        //         $count                   = 0;
        //     }
        // }

        return [
            'charge_late_payment' => $total_debt, 'pay_debt' =>$pay_debt, 'debt_total'=>$debt_total,
        ];
    }

    /**
     * Quote calculate.
     *
     * @param float $interest
     * @param int   $total_payments
     * @param float $amount
     */
    public function pmt($interest, $payments, $amount)
    {
        $first_value  = $interest * pow((1 + $interest), $payments);
        $second_value = pow((1 + $interest), $payments) - 1;
        $pmt          = $amount * ($first_value / $second_value);

        return round($pmt, 4);
    }

    /**
     * Check equivalent rate weekly or anual.
     *
     * @param string $type
     */
    public function checkEquivalentRate($type)
    {
        return $this->settings->value('TASA_ANUAL') / ('Q' == $type ? 52.143 : 12) / 100;
    }

    /**
     * Check total days for calculations.
     *
     * @param string $type
     */
    public function checkTotalDays($type)
    {
        return 'Q' === $type ? (7 * 4) : (30 * 4);
    }
}

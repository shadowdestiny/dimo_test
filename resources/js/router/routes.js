import Login from '@/pages/auth/login';
import Recovery from '@/pages/auth/recovery';
import ValidationPassword from '@/pages/auth/validation_password';
import ClientList from '@/pages/clientList';
import ClientDetail from '@/pages/clientDetail';
import LoanDetail from '@/pages/loanDetail';
import LoanList from '@/pages/loanList';
import recordList from '@/pages/recordList';
import Answer from '@/pages/answer';
import Step from '@/pages/step';
import Question from '@/pages/question';
import Configuration from '@/pages/configuration';
import Home from '@/pages/home';

export default [
    //auth
    { path: "/", name: "login", component: Login },
    { path: "/recovery", name: "recovery", component: Recovery },
    { path: "/validation_password/:token", name: "validation_password", component: ValidationPassword },

    //cms
    { path: "/home", name: "home", component: Home },
    { path: "/client_list/:status", name: "client_list", component: ClientList },
    { path: "/client_detail/:client_id", name: "client_detail", component: ClientDetail },
    { path: "/loan_list/:status", name: "loan_list", component: LoanList },
    { path: "/loan_detail/:loan_id", name: "loan_detail", component: LoanDetail },
    { path: "/record_list", name: "record_list", component: recordList },
    { path: "/answers/:uuid", name: "answer", component: Answer },
    { path: "/step", name: "step", component: Step },
    { path: "/question/:question_uuid", name: "question", component: Question },
    { path: "/configuration", name: "configuration", component: Configuration},

];

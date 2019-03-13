import axios from "axios";

// actions
export const actions = {

    async clients({ dispatch },params) {
        try {

            return axios.get("/api/clients/clients/"+params.type+"/"+params.status);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async loansCount({ dispatch }, type) {
        try {

            return axios.get("/api/loans/counts/"+type);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async saveRecord({ dispatch }, params) {
        try {

            return axios.post("/api/clients/record/save",params);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

};

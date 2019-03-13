import axios from "axios";

// actions
export const actions = {

    async loan({ dispatch }, uuid) {
        try {

            return axios.get("/api/loans/"+uuid);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async loans({ dispatch }, type) {
        try {

            return axios.get("/api/loans/loans/"+type); //default all

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

};

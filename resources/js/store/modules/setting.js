import axios from "axios";

// actions
export const actions = {


    async get({ dispatch }) {
        try {
            return axios.get("/api/settings/list");
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async update({ dispatch },params) {
        try {
            return axios.post("/api/settings/update",params);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

};

import axios from "axios";

// actions
export const actions = {


    async get({ dispatch },level_uuid) {
        try {
            return axios.get("/api/level_amounts/list/"+level_uuid);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async update({ dispatch },params) {
        try {
            return axios.post("/api/level_amounts/update",params);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

};

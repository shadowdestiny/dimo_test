import axios from "axios";

// actions
export const actions = {

    async list({ dispatch }, uuid) {
        try {

            return axios.get("/api/questions/list/"+uuid);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async update({ dispatch }, params) {
        try {

            return axios.post("/api/questions/update/",params);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async delete({ dispatch }, params) {
        try {

            return axios.post("/api/questions/delete/",params);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async add({ dispatch }, params) {
        try {

            return axios.post("/api/questions/add/",params);

        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

};

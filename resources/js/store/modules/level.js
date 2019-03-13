import axios from "axios";

// actions
export const actions = {


    async get({ dispatch }) {
        try {
            return axios.get("/api/levels/");
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async getOne({ dispatch },id) {
        try {
            return axios.get("/api/levels/"+id);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async update({ dispatch },data) {
        try {
            return axios.post("/api/levels/update",data);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

};

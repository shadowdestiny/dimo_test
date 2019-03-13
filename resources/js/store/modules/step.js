import axios from "axios";

// actions
export const actions = {
    async steps({ dispatch }, id) {
        try {
            return axios.get("/api/steps/steps/" + id);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async client({ dispatch }, id) {
        try {
            return axios.get("/api/steps/client_steps/" + id);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async listSteps({ dispatch }) {
        try {
            return axios.get("/api/steps/steps_list/list");
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async addStep({ dispatch }, params) {
        try {
            return axios.post("/api/steps/steps/add", params);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async dropStep({ dispatch }, params) {
        try {
            return axios.post("/api/steps/steps/drop", params);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    },

    async editStep({ dispatch }, params) {
        try {
            return axios.post("/api/steps/steps/edit", params);
        } catch (e) {
            const error = e.response.data.error;
            throw error;
        }
    }
};

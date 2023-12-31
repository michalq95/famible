import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
    state: {
        user: {
            data: JSON.parse(sessionStorage.getItem("USER")) || {},
            token: sessionStorage.getItem("TOKEN"),
        },
        currentRoom: { loading: false, data: [] },
        commonTaskStatuses: [
            { name: "Pending", value: 0 },
            { name: "On It", value: 1 },
            { name: "Complications", value: 2 },
            { name: "Done", value: 3 },
        ],
        error: "",
    },
    getters: {
        isMod(state) {
            return state.user.data.role < 2;
        },
        commonTaskStatuses(state) {
            return state.commonTaskStatuses;
        },
    },
    actions: {
        register({ commit }, user) {
            return axiosClient.post("/register", user).then(({ data }) => {
                commit("setUser", data);
                return data;
            });
        },
        login({ commit }, user) {
            return axiosClient.post("/login", user).then(({ data }) => {
                commit("setUser", data);
                return data;
            });
        },
        logout({ commit }) {
            return axiosClient.post("/logout").then((data) => {
                commit("logout");

                return data;
            });
        },
        errorMessage({ commit }, message) {
            commit("setError", message);
        },
        getRoom({ commit }, id) {
            commit("setCurrentRoomLoading", true);
            return axiosClient
                .get(`/room/${id}`)
                .then((res) => {
                    commit("setCurrentRoom", res.data.data);
                    commit("setCurrentRoomLoading", false);
                    return res;
                })
                .catch((err) => {
                    commit("setCurrentRoomLoading", false);
                    throw err;
                });
        },
        savePost({ commit, state }, { formData, id }) {
            let response;
            if (id) {
                response = axiosClient
                    .post(
                        `/room/${state.currentRoom.data.id}/post/${id}`,
                        formData
                    )
                    .then((res) => {
                        let room = state.currentRoom.data;
                        const existingObjIndex = room.posts.findIndex(
                            (obj) => obj.id === res.data.data.id
                        );

                        if (existingObjIndex !== -1) {
                            room.posts[existingObjIndex] = res.data.data;
                            commit("setCurrentRoom", room);
                        }

                        return res.data.data;
                    });
            } else {
                response = axiosClient
                    .post(`/room/${state.currentRoom.data.id}/post`, formData)
                    .then((res) => {
                        let room = state.currentRoom.data;
                        room.posts.push(res.data.data);
                        commit("setCurrentRoom", room);
                        return res.data.data;
                    });
            }
            return response;
        },
        saveRoom({ commit, state }, room) {
            let response = axiosClient.post(`/room`, room).then((res) => {
                commit("setAccesses", res.data.data);
                return res.data;
            });
            return response;
        },
        refreshUser({ commit, state }) {
            const response = axiosClient.get(`user`).then((res) => {
                const data = { token: state.user.token, user: res.data.data };
                commit("setUser", data);
                return res.data;
            });
            return response;
        },
    },
    mutations: {
        setError: (state, value) => {
            state.error = value;
        },
        set404Error: (state, value) => {
            state.error404 = value;
        },
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
            state.companyApplications = {};
            sessionStorage.removeItem("TOKEN");
            sessionStorage.removeItem("USER");
        },
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem("USER", JSON.stringify(userData.user));

            sessionStorage.setItem("TOKEN", userData.token);
        },
        setCurrentRoomLoading(state, status) {
            state.currentRoom.loading = status;
        },
        setCurrentRoom(state, data) {
            state.currentRoom.data = data;
        },
        setAccesses(state, data) {
            state.user.data.accesses = data;
        },
    },
    modules: {},
});

export default store;

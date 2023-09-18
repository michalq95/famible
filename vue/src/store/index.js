import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
    state: {
        user: {
            data: JSON.parse(sessionStorage.getItem("USER")) || {},
            token: sessionStorage.getItem("TOKEN"),
        },
        currentRoom: { loading: false, data: [] },
    },
    getters: {
        isMod(state) {
            return state.user.data.role < 2;
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
        getRoom({ commit }, id) {
            commit("setCurrentRoomLoading", true);
            return axiosClient
                .get(`/room/${id}`)
                .then((res) => {
                    commit("setCurrentRoom", res.data);
                    commit("setCurrentRoomLoading", false);
                    return res;
                })
                .catch((err) => {
                    commit("setCurrentRoomLoading", false);
                    throw err;
                });
        },
    },
    mutations: {
        setError: (state, value) => {
            state.error = value;
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
            state.currentRoom.data = data.data;
        },
    },
    modules: {},
});

export default store;

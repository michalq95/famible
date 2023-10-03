import { createRouter, createWebHistory } from "vue-router";
import AuthLayout from "../components/AuthLayout.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Room from "../views/Room.vue";
import Dashboard from "../views/Dashboard.vue";
import Home from "../views/Home.vue";
import NotFound from "../views/NotFound.vue";
import NewRoom from "../views/NewRoom.vue";
import Settings from "../views/Settings.vue";
import store from "../store";

const routes = [
    {
        path: "/auth",
        name: "Auth",
        redirect: "/login",
        component: AuthLayout,
        meta: { isGuest: true },
        children: [
            { path: "/login", name: "Login", component: Login },
            { path: "/register", name: "Register", component: Register },
        ],
    },
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        meta: { requiresAuth: true },
    },
    {
        path: "/createroom",
        name: "CreateRoom",
        component: NewRoom,
        meta: { requiresAuth: true },
    },
    {
        path: "/settings",
        name: "Settings",
        component: Settings,
        meta: { requiresAuth: true },
    },
    {
        path: "/rooms/:id",
        name: "RoomView",
        component: Room,
        meta: { requiresAuth: true },
    },
    { path: "/404", name: "NotFound", component: NotFound },
    { path: "/:pathMatch(.*)*", redirect: { name: "NotFound" } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.name != "NotFound") store.commit("set404Error", false);
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: "Login" });
    } else if (store.state.user.token && to.meta.isGuest) {
        next({ name: "Dashboard" });
    } else {
        next();
    }
});
export default router;

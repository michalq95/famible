import axiosClient from "./axios";

export function getPendingAccessess() {
    return axiosClient.get(`user/pending`).then((res) => {
        return res.data;
    });
}

export async function findUsers({ keyword = null } = {}) {
    const res = await axiosClient.get(`user/query`, {
        params: { keyword: keyword },
    });
    return res.data;
}
export async function inviteUser({ user_id, room_id, role }) {
    const res = await axiosClient.post(`access`, {
        user_id,
        room_id,
        role,
    });
    return res.data;
}
export async function modifyAccess(data) {
    const res = await axiosClient.put(`access/${data.access_id}`, data);
    return res.data;
}

export async function readOne(id) {
    const res = await axiosClient.post(`user/markasread`, { id: id });
    return res.data;
}
export async function readAll() {
    const res = await axiosClient.post(`user/markallasread`);
    return res.data;
}

export function savePost(post) {}

import axiosClient from "./axios";

export function getCompanyApplications() {
  return axiosClient.get(`application/company`).then((res) => {
    return res.data;
  });
}

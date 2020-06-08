export const requestHandlers = {
    handleAxiosError(error, alerts) {
        if (error.response.status === 422) {
            alerts.error(error.response.data.error);
        } else if (error.response.status === 403) {
            alerts.error("Похоже, что у Вас нет прав на выполнение этого действия. Попробуйте перезагрузить страницу");
        } else {
            alerts.error("Что-то пошло не так, попробуйте перезагрузить страницу");
        }
    }
};

import Noty from "noty";

export const notification = {
    config: {
        timeout: 5000,
        type: "alert",
        theme: "mint",
        layout: "topRight"
    },

    create(message, type, timeout) {
        let config = notification.config;
        return new Noty({
            type: type || config.type,
            text: message || '',
            timeout: timeout || config.timeout
        }).show();
    },

    info(message) {
        return notification.create(`<b>Информация:</b><br>${message}`, "info");
    },
    success(message) {
        return notification.create(`<b>Успех:</b><br>${message}`, "success");
    },
    warning(message) {
        return notification.create(`<b>Предупреждение:</b><br>${message}`, "warning");
    },
    error(message) {
        return notification.create(`<b>Ошибка:</b><br>${message}`, "error");
    },

    setDefault() {
        if (Noty) {
            Noty.overrideDefaults({
                layout   : notification.config.layout,
                theme    : notification.config.theme,
            });
        } else {
            console.warn("Noty is not defined!");
        }
    }
};

notification.setDefault();

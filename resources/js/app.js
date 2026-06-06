import "../css/app.css";
import "./bootstrap";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "ziggy-js";

// ⭐ Global safe route function – always available, never throws
window.route = function (name, params = {}) {
    // Use Ziggy's internal function if available
    if (typeof window._route !== "undefined") {
        try {
            return window._route(name, params);
        } catch (e) {}
    }
    // Fallback using Ziggy route data (if injected)
    if (window.Ziggy && window.Ziggy.routes && window.Ziggy.routes[name]) {
        let uri = window.Ziggy.routes[name].uri;
        for (let key in params) {
            uri = uri.replace(`{${key}}`, params[key]);
        }
        return "/" + uri;
    }
    // Ultimate fallback: dots become slashes
    return "/" + name.replace(/\./g, "/");
};

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, window.Ziggy)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});

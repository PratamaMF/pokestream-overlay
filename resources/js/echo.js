import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: import.meta.env.VITE_BROADCAST_CONNECTION || "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || "ap1",
    forceTLS: true,
    enabledTransports: ["ws", "wss"],
});

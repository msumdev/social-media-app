import mitt from 'mitt';

const emitter = mitt();
let websocket;

export function initializeWebSocket(token) {
    if (!token) {
        console.error("JWT token not found!");
        return;
    }

    const addresses = [
        'wss://meetzy.mcsportfolio.com/app',
    ]

    const host = addresses[Math.floor(Math.random() * addresses.length)];

    websocket = new WebSocket(`${host}/?token=${token}`);

    websocket.onopen = () => {
        console.log("WebSocket connection established");
    };

    websocket.onclose = () => {
        console.log("WebSocket connection closed");
    };

    websocket.onmessage = (event) => {
        const message = JSON.parse(event.data);

        emitter.emit(message.type, message);
    };

    websocket.onerror = (error) => {
        console.error("WebSocket error:", error);
    };
}

export { emitter, websocket };

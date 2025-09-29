const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const axios = require('axios');

// Load .env
require('dotenv').config();

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
    cors: { origin: "*" }
});

io.on('connection', (socket) => {
    console.log("===> Socket ID Connected: ", socket.id);
    console.log("===> Socket Connected: ", socket.connected);
    console.log("===> Socket Current Rooms: ", socket.rooms);
    console.log("===> Socket Handshake Query: ", socket.handshake.query);

    socket.on("goLive", async (data) => {
        try {
            // socket.off("liveChat");

            const dataOfgoLive = data;  // user_id, room_id
            console.log("===> goLive Data:  ", dataOfgoLive);

            const roomId = dataOfgoLive.room_id;
            socket.join(roomId); // Join room only on "goLive"
            console.log(`===> Socket ${socket.id} joined room ${roomId}`);

            // Add Live History & User
            const apiAddLiveHistory = `${process.env.APP_URL}/public/addlivehistory`;
            const response = await axios.post(apiAddLiveHistory, { user_id: dataOfgoLive.user_id, room_id: dataOfgoLive.room_id });
            console.log("===> goLive Response: ", response.data);

        } catch (error) {
            console.error('===> Error goLive: ', error.response ? error.response.data : error.message);
        }
    });
    socket.on("endLive", async (data) => {
        try {

            const dataOfendLive = data;  // user_id, room_id
            console.log("===> endLive Data:  ", dataOfendLive);

            // End Live
            const apiEndLive = `${process.env.APP_URL}/public/endlive`;
            const response = await axios.post(apiEndLive, { user_id: dataOfendLive.user_id, room_id: dataOfendLive.room_id });
            console.log("===> endLive Response: ", response.data);

            const roomId = dataOfendLive.room_id;
            // Emit roomDeleted event
            io.in(roomId).emit('roomDeleted', { room_id: roomId, is_close: true });

            const socketsInRoom = await io.in(roomId).fetchSockets();
            socketsInRoom.forEach((socket) => {
                socket.leave(roomId);
                console.log(`===> Socket ${socket.id} left room ${roomId}`);
            });
        } catch (error) {
            console.error("===> Error endLive: ", error.response ? error.response.data : error.message);
        }
    });

    socket.on("addView", async (data) => {
        try {
            const dataOfaddView = data;  // user_id, room_id
            console.log("===> addView Data:  ", dataOfaddView);

            // Ensure both user_id and room_id exist
            if (!dataOfaddView.user_id || !dataOfaddView.room_id) {
                throw new Error("Invalid user_id or room_id in addView data");
            }

            // Add View
            const apiaddView = `${process.env.APP_URL}/public/addview`;
            const response = await axios.post(apiaddView, { user_id: dataOfaddView.user_id, room_id: dataOfaddView.room_id });
            console.log("===> addView Response: ", response.data);

            const roomId = dataOfaddView.room_id;
            socket.join(roomId);
            console.log(`===> Socket ${socket.id} joined room ${roomId}`);

            io.in(roomId).emit('addViewCountToClient', response.data.result.live_count);
        } catch (error) {
            console.error("===> Error addView: ", error.response ? error.response.data : error.message);
        }
    });
    socket.on("lessView", async (data) => {
        try {
            const dataOflessView = data;  // user_id, room_id
            console.log("===> lessView Data:  ", dataOflessView);

            // Ensure both user_id and room_id exist
            if (!dataOflessView.user_id || !dataOflessView.room_id) {
                throw new Error("Invalid user_id or room_id in addView data");
            }

            // Less View
            const apilessView = `${process.env.APP_URL}/public/lessview`;
            const response = await axios.post(apilessView, { user_id: dataOflessView.user_id, room_id: dataOflessView.room_id });
            console.log("===> lessView Response: ", response.data);

            const roomId = dataOflessView.room_id;
            socket.leave(roomId);
            console.log(`===> Socket ${socket.id} left room ${roomId}`);

            io.in(roomId).emit('addViewCountToClient', response.data.result.live_count);
        } catch (error) {
            console.error("===> Error lessView: ", error.response ? error.response.data : error.message);
        }
    });
    socket.on("liveChat", async (data) => {
        try {

            const dataOfliveChat = data;  // user_id, room_id, comment
            console.log("===> liveChat Data:  ", dataOfliveChat);

            // Live Chat
            const apiLiveChat = `${process.env.APP_URL}/public/livechat`;
            const response = await axios.post(apiLiveChat, {
                user_id: dataOfliveChat.user_id,
                room_id: dataOfliveChat.room_id,
                comment: dataOfliveChat.comment
            });
            console.log("===> liveChat Response: ", response.data);

            const roomId = dataOfliveChat.room_id;

            const clientsInRoom = await io.in(roomId).fetchSockets();
            console.log(`===> Clients in room ${roomId}: `, clientsInRoom.length);

            io.in(roomId).emit('liveChatToClient', response.data.result);
        } catch (error) {
            console.error("===> Error liveChat: ", error.response ? error.response.data : error.message);
        }
    });

    socket.on("sendGift", async (data) => {
        try {
            const dataOfsendGift = data;  // user_id, room_id, gift_id
            console.log("===> sendGift Data:  ", dataOfsendGift);

            // Send Gift
            const apiSendGift = `${process.env.APP_URL}/public/sendgift`;
            const response = await axios.post(apiSendGift, {
                user_id: dataOfsendGift.user_id,
                room_id: dataOfsendGift.room_id,
                gift_id: dataOfsendGift.gift_id
            });
            console.log("===> sendGift Response: ", response.data);

            const roomId = dataOfsendGift.room_id;
            io.in(roomId).emit('sendGiftToClient', response.data.result);
        } catch (error) {
            console.error("===> Error sendGift: ", error.response ? error.response.data : error.message);
        }
    });
    
    // socket.on('disconnect', () => {
    //     console.log('===> Socket ID Disconnected: ', socket.id);
    // });
    socket.on('disconnect', async (reason) => {
        try {
            const socketRooms = Array.from(socket.rooms).filter(room => room !== socket.id);

            for (const roomId of socketRooms) {
                socket.leave(roomId);

                const socketsInRoom = await io.in(roomId).fetchSockets();
                if (socketsInRoom.length === 0) {
                    console.log(`===> Room ${roomId} is empty. Cleaning up...`);

                    // Emit roomDeleted event
                    io.in(roomId).emit('roomDeleted', { room_id: roomId, is_close: true });

                    // Remove the room from the in-memory Map
                    rooms.delete(roomId);
                    console.log(`===> Room ${roomId} deleted from memory.`);
                }
            }
        } catch (error) {
            console.error('===> Error during disconnect cleanup: ', error.message);
        }
    });

});

// Start the server
server.listen(3000, () => {
    console.log("===> Socket.io Server Running on Port 3000 <===");
});

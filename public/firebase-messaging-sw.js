importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js');

// ده الـ Config بتاعك اللي في الصورة
firebase.initializeApp({
    apiKey: "AIzaSyBrCXV6pfct3tlGHudO-Fx3G8hr5J-0tSg",
    authDomain: "smart-fleet-121fc.firebaseapp.com",
    projectId: "smart-fleet-121fc",
    storageBucket: "smart-fleet-121fc.firebasestorage.app",
    messagingSenderId: "379304020890",
    appId: "1:379304020890:web:65ff4268ca6de996260823",
    measurementId: "G-K708RFEER3"
});

const messaging = firebase.messaging();

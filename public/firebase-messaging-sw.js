// Firebase Messaging Service Worker
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

const firebaseConfig = {
  apiKey: "AIzaSyBpyCu8DzHm-sEV8vQWeOpvELMKwEeaBAI",
  authDomain: "dohelp-7d140.firebaseapp.com",
  projectId: "dohelp-7d140",
  storageBucket: "dohelp-7d140.firebasestorage.app",
  messagingSenderId: "228064919901",
  appId: "1:228064919901:web:b6d1f42822b129419ea8b4",
  measurementId: "G-61V67TYW8N",
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Handle background messages (when app is not active)
// Do not show notification here, let foreground handle it
messaging.onBackgroundMessage((payload) => {
  console.log('Background message received:', payload);
  // Do not show notification in background
  // Notifications will be handled by foreground onMessage listener
});

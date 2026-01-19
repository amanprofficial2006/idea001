importScripts("https://www.gstatic.com/firebasejs/12.8.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/12.8.0/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "AIzaSyBpyCu8DzHm-sEV8vQWeOpvELMKwEeaBAI",
  authDomain: "dohelp-7d140.firebaseapp.com",
  projectId: "dohelp-7d140",
  storageBucket: "dohelp-7d140.firebasestorage.app",
  messagingSenderId: "228064919901",
  appId: "1:228064919901:web:b6d1f42822b129419ea8b4",
  measurementId: "G-61V67TYW8N",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  self.registration.showNotification(
    payload.notification.title,
    {
      body: payload.notification.body,
      icon: "/logo.png",
    }
  );
});

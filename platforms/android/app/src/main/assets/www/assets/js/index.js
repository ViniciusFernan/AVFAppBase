/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

var urlWebservices="http://avfweb.com.br";

var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },

    // Bind Event Listeners
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {
        var parentElement = document.getElementById(id);
        parentElement.setAttribute('style', 'display:block;');
    },


    splashShow: function(){
        var color =  'rgb( 0, 153, 225)';
        var innerHTML = '<div id="splash" style="background-color: '+ color +'; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px;"><img src="./img/logo.png" style="position: absolute; top: 50%; left: 50%; height: 200px; width: 170px; margin-top: -100px; margin-left: -85px;"></div>';
        document.body.insertAdjacentHTML('beforeend', innerHTML);
    },

    splashHide: function (){
        var fadeSplash = document.getElementById("splash");
        var fadeEffect = setInterval(function() {
            if (!fadeSplash.style.opacity) { fadeSplash.style.opacity = 1; }

            if (fadeSplash.style.opacity > 0) {
                fadeSplash.style.opacity -= 0.1;

                if (fadeSplash.parentNode) {
                    fadeSplash.parentNode.removeChild(fadeSplash);
                }

            } else {
                clearInterval(fadeEffect);
            }

        }, 800);

    },

    isOnline: function(){
        var rede =  navigator.connection.type;
        return rede === "none" || rede === null ||  rede === "unknown" ? false : true;
    },

    appKey: function(){
        return ( window.localStorage.getItem('serial') ? window.localStorage.getItem('serial') : null );
    },

    logout: function () {
        window.localStorage.clear();
        window.location="index.html";
    }




};

app.initialize();

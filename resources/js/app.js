import './bootstrap';
import { Application } from "./application/app.js";
import { legacyApp } from "./application/legacy.js";

window.Vue = require("vue");
window.Noty = require("noty");

window.Application = Application;
window.legacyApp = legacyApp;

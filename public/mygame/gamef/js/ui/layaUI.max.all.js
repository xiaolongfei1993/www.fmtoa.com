var __extends = (this && this.__extends) || (function () {
    var extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var View = laya.ui.View;
var Dialog = laya.ui.Dialog;
var ui;
(function (ui) {
    var GameInfoUI = /** @class */ (function (_super) {
        __extends(GameInfoUI, _super);
        function GameInfoUI() {
            return _super.call(this) || this;
        }
        GameInfoUI.prototype.createChildren = function () {
            _super.prototype.createChildren.call(this);
            this.createView(ui.GameInfoUI.uiView);
        };
        GameInfoUI.uiView = { "type": "View", "props": { "width": 480, "height": 852 }, "child": [{ "type": "Button", "props": { "y": 55, "x": 391, "width": 60, "var": "pauseBtn", "stateNum": "1", "skin": "war/btn_pause.png", "height": 45 } }, { "type": "Label", "props": { "y": 55, "x": 20, "width": 80, "var": "hpLabel", "text": "HP:5", "height": 33, "fontSize": 24, "color": "#5fff05", "align": "center" } }, { "type": "Label", "props": { "y": 55, "x": 102, "width": 106, "var": "levelLabel", "text": "Level:50", "height": 33, "fontSize": 24, "color": "#fbfbfb", "align": "center" } }, { "type": "Label", "props": { "y": 55, "x": 213, "width": 79, "var": "scoreLabel", "text": "Score:100", "height": 33, "fontSize": 24, "color": "#fdc105", "align": "center" } }, { "type": "Label", "props": { "y": 427, "x": 21, "wordWrap": true, "width": 445, "var": "infoLabel", "text": "战斗结束", "height": 136, "fontSize": 24, "color": "#fbfbfb", "align": "center" } }, { "type": "ProgressBar", "props": { "y": 26, "x": 27, "var": "timeBar", "value": 1, "skin": "war/progress_time.png" } }] };
        return GameInfoUI;
    }(View));
    ui.GameInfoUI = GameInfoUI;
})(ui || (ui = {}));
(function (ui) {
    var GameStartUI = /** @class */ (function (_super) {
        __extends(GameStartUI, _super);
        function GameStartUI() {
            return _super.call(this) || this;
        }
        GameStartUI.prototype.createChildren = function () {
            _super.prototype.createChildren.call(this);
            this.createView(ui.GameStartUI.uiView);
        };
        GameStartUI.uiView = { "type": "View", "props": { "y": 0, "x": 0, "width": 480, "height": 852 }, "child": [{ "type": "Image", "props": { "y": 0, "x": 0, "width": 480, "var": "bot", "skin": "war/smbg.png", "height": 852 } }, { "type": "Button", "props": { "y": 533, "x": 124, "width": 225, "var": "start", "stateNum": "2", "skin": "war/btn_start.png", "height": 102 } }, { "type": "Label", "props": { "y": 819, "x": 127, "width": 301, "text": "技术支持:  河南野人智能科技", "height": 28, "fontSize": 18, "font": "Helvetica", "color": "#f9f6f6" } }] };
        return GameStartUI;
    }(View));
    ui.GameStartUI = GameStartUI;
})(ui || (ui = {}));
//# sourceMappingURL=layaUI.max.all.js.map
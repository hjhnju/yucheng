(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define('common/extra/Gallery/js/blueimp-gallery-vimeo', [
            './blueimp-helper',
            './blueimp-gallery-video'
        ], factory);
    } else {
        factory(window.blueimp.helper || window.jQuery, window.blueimp.Gallery);
    }
}(function ($, Gallery) {
    'use strict';
    if (!window.postMessage) {
        return Gallery;
    }
    $.extend(Gallery.prototype.options, {
        vimeoVideoIdProperty: 'vimeo',
        vimeoPlayerUrl: '//player.vimeo.com/video/VIDEO_ID?api=1&player_id=PLAYER_ID',
        vimeoPlayerIdPrefix: 'vimeo-player-',
        vimeoClickToPlay: true
    });
    var textFactory = Gallery.prototype.textFactory || Gallery.prototype.imageFactory, VimeoPlayer = function (url, videoId, playerId, clickToPlay) {
            this.url = url;
            this.videoId = videoId;
            this.playerId = playerId;
            this.clickToPlay = clickToPlay;
            this.element = document.createElement('div');
            this.listeners = {};
        }, counter = 0;
    $.extend(VimeoPlayer.prototype, {
        canPlayType: function () {
            return true;
        },
        on: function (type, func) {
            this.listeners[type] = func;
            return this;
        },
        loadAPI: function () {
            var that = this, apiUrl = '//' + (location.protocol === 'https' ? 'secure-' : '') + 'a.vimeocdn.com/js/froogaloop2.min.js', scriptTags = document.getElementsByTagName('script'), i = scriptTags.length, scriptTag, called, callback = function () {
                    if (!called && that.playOnReady) {
                        that.play();
                    }
                    called = true;
                };
            while (i) {
                i -= 1;
                if (scriptTags[i].src === apiUrl) {
                    scriptTag = scriptTags[i];
                    break;
                }
            }
            if (!scriptTag) {
                scriptTag = document.createElement('script');
                scriptTag.src = apiUrl;
            }
            $(scriptTag).on('load', callback);
            scriptTags[0].parentNode.insertBefore(scriptTag, scriptTags[0]);
            if (/loaded|complete/.test(scriptTag.readyState)) {
                callback();
            }
        },
        onReady: function () {
            var that = this;
            this.ready = true;
            this.player.addEvent('play', function () {
                that.hasPlayed = true;
                that.onPlaying();
            });
            this.player.addEvent('pause', function () {
                that.onPause();
            });
            this.player.addEvent('finish', function () {
                that.onPause();
            });
            if (this.playOnReady) {
                this.play();
            }
        },
        onPlaying: function () {
            if (this.playStatus < 2) {
                this.listeners.playing();
                this.playStatus = 2;
            }
        },
        onPause: function () {
            this.listeners.pause();
            delete this.playStatus;
        },
        insertIframe: function () {
            var iframe = document.createElement('iframe');
            iframe.src = this.url.replace('VIDEO_ID', this.videoId).replace('PLAYER_ID', this.playerId);
            iframe.id = this.playerId;
            this.element.parentNode.replaceChild(iframe, this.element);
            this.element = iframe;
        },
        play: function () {
            var that = this;
            if (!this.playStatus) {
                this.listeners.play();
                this.playStatus = 1;
            }
            if (this.ready) {
                if (!this.hasPlayed && (this.clickToPlay || window.navigator && /iP(hone|od|ad)/.test(window.navigator.platform))) {
                    this.onPlaying();
                } else {
                    this.player.api('play');
                }
            } else {
                this.playOnReady = true;
                if (!window.$f) {
                    this.loadAPI();
                } else if (!this.player) {
                    this.insertIframe();
                    this.player = $f(this.element);
                    this.player.addEvent('ready', function () {
                        that.onReady();
                    });
                }
            }
        },
        pause: function () {
            if (this.ready) {
                this.player.api('pause');
            } else if (this.playStatus) {
                delete this.playOnReady;
                this.listeners.pause();
                delete this.playStatus;
            }
        }
    });
    $.extend(Gallery.prototype, {
        VimeoPlayer: VimeoPlayer,
        textFactory: function (obj, callback) {
            var options = this.options, videoId = this.getItemProperty(obj, options.vimeoVideoIdProperty);
            if (videoId) {
                if (this.getItemProperty(obj, options.urlProperty) === undefined) {
                    obj[options.urlProperty] = '//vimeo.com/' + videoId;
                }
                counter += 1;
                return this.videoFactory(obj, callback, new VimeoPlayer(options.vimeoPlayerUrl, videoId, options.vimeoPlayerIdPrefix + counter, options.vimeoClickToPlay));
            }
            return textFactory.call(this, obj, callback);
        }
    });
    return Gallery;
}));
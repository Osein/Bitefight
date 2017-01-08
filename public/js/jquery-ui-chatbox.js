/*
 * Copyright 2010, Wen Pu (dexterpu at gmail dot com)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Check out http://www.cs.illinois.edu/homes/wenpu1/chatbox.html for document
 *
 * Depends on jquery.ui.core, jquery.ui.widiget, jquery.ui.effect
 *
 * Also uses some styles for jquery.ui.dialog
 *
 */


// TODO: implement destroy()
(function($) {
    $.widget("ui.chatbox", {
        options: {
            id: null, //id for the DOM element
            title: null, // title of the chatbox
            user: null, // can be anything associated with this chatbox
            hidden: false,
            offset: 0, // relative to right edge of the browser window
            width: 300, // width of the chatbox
            minimized: false,
            closed: false,
            onlinePlayer: new Array(),
            messageSent: function(id, user, msg) {
                // override this
                this.boxManager.addMsg(user.checksum, msg);
            },
            boxClosed: function(id) {
                this.closed = true;

                if(messageListener){
                    window.clearInterval(messageListener);
                    messageListener = null;
                }
                $('#chat_div').remove();

                $.totalStorage(this.user.clanId + '_' + this.user.id + '_clanChat', false);
                $.totalStorage(this.user.clanId + '_' + this.user.id + '_clanChatMessages', false);
                $.totalStorage(this.user.clanId + '_' + this.user.id + '_clanChatOnlinePlayerString', false);
            }, // called when the close icon is clicked
            boxManager: {
                // thanks to the widget factory facility
                // similar to http://alexsexton.com/?p=51
                init: function(elem) {
                    this.elem = elem;
                    var box = this.elem.uiChatboxLog;

                    // Init with stored messages if there are some
                    if ($.totalStorage(elem.options.user.clanId + '_' + elem.options.user.id + '_clanChat')) {
                        box.chatbox('option', 'boxManager').displayMessages($.totalStorage(elem.options.user.clanId + '_' + elem.options.user.id + '_clanChatMessages'), false);
                        box.chatbox('option', 'boxManager').displayOnlinePlayer($.totalStorage(elem.options.user.clanId + '_' + elem.options.user.id + '_clanChatOnlinePlayerString'));
                    }

                    /*
                     * Init messageListener (every 2 seconds)
                     * Listens to online player and messages
                     */
                    messageListener = window.setInterval(function() {
                        // Listen to online player - and poll!
                        var requestData = {
                            'onlinePlayer': elem.options.onlinePlayer
                        };

                        $.getJSON('/ajax/clanchat/ajaxGetOnlinePlayer', requestData, function(message) {
                            elem.options.onlinePlayer = message.onlinePlayer;
                            $.totalStorage(elem.options.user.clanId + '_' + elem.options.user.id + '_clanChatOnlinePlayerString', message.onlinePlayerString);
                            box.chatbox('option', 'boxManager').displayOnlinePlayer(message.onlinePlayerString);
                        });


                        // Listen to messages
                        var lastMessageId = null;
                        if ($('#chat_div').children('div.ui-chatbox-entry').length > 0) {
                            var lastMessageDiv = $('#chat_div').children('div.ui-chatbox-entry').eq(-1);
                            lastMessageId = $(lastMessageDiv).attr('id').split("_").slice(1).join('_');

                            requestData = {
                                'lastMessageId': lastMessageId
                            };
                        } else {
                            requestData = {
                            };
                        }
                        elem.uiChatboxLog.chatbox('option', 'boxManager').getMessages(requestData);
                    }, 2000);
                },
                addMsg: function(userChecksum, msg) {
                    var self = this;
                    var box = self.elem.uiChatboxLog;

                    var requestData = {
                        'checksum': userChecksum,
                        'type': 'message',
                        'message': msg,
                        'currentChannel': 'clan',
                        'isImportant': false
                    };

                    $.getJSON('/ajax/clanchat/ajaxWriteMessage', requestData, function(data) {
                        if (typeof data !== 'boolean') {
                            var lastMessageId = null;
                            if ($('#chat_div').children('div.ui-chatbox-entry').length > 0) {
                                var lastMessageDiv = $('#chat_div').children('div.ui-chatbox-entry').eq(-1);
                                lastMessageId = $(lastMessageDiv).attr('id').split("_").slice(1).join('_');

                                var requestData = {
                                    'lastMessageId': lastMessageId
                                };
                            } else {
                                var requestData = {
                                };
                            }

                            box.chatbox("option", "boxManager").getMessages(requestData);
                        }
                    });
                },
                getMessages: function(requestData) {
                    var self = this;

                    if (self.getMessageLock) {
                        return;
                    }

                    self.getMessageLock = true;
                    box = this.elem.uiChatboxLog;
                    $.getJSON('/ajax/clanchat/ajaxGetMessages', requestData, function(messages) {
                        if (messages !== false) {
                            if ($.totalStorage(self.elem.options.user.clanId + '_' + self.elem.options.user.id + '_clanChatMessages') === false) {
                                $.totalStorage(self.elem.options.user.clanId + '_' + self.elem.options.user.id + '_clanChatMessages', messages);
                            } else {
                                var storedMessages = $.parseJSON($.totalStorage(self.elem.options.user.clanId + '_' + self.elem.options.user.id + '_clanChatMessages'));

                                if (Array.isArray(storedMessages)) {
                                    var i = 0;
                                    while (i < messages.length) {
                                        storedMessages.push(messages[i]);
                                        i++;
                                    }
                                } else {
                                    storedMessages = messages;
                                }

                                $.totalStorage(self.elem.options.user.clanId + '_' + self.elem.options.user.id + '_clanChatMessages', storedMessages);
                            }
                            $.totalStorage(self.elem.options.user.clanId + '_' + self.elem.options.user.id + '_clanChat', true);

                            box.chatbox('option', 'boxManager').displayMessages(messages, true);

                            if (!box.chatbox('option', 'boxManager').elem.uiChatboxTitlebar.hasClass('ui-state-focus')
                                && !box.chatbox('option', 'closed')) {
                                box.chatbox('option', 'boxManager').highlightBox();
                            }
                        }

                        self.getMessageLock = false;
                    });
                },
                displayMessages: function(messages, fadeIn) {
                    box = this.elem.uiChatboxLog;

                    if (typeof messages == 'string') {
                        messages = $.parseJSON(messages);
                    }

                    var todayDateString = new Date().toLocaleDateString();
                    var diplayedDateStrings = [];

                    if (Array.isArray(messages)) {
                        messages.forEach(function(message) {

                            // Show date if there is a date change

                            var date = new Date(message.ts * 1000);
                            var dateString = date.toLocaleDateString();

                            if ((dateString != todayDateString || (dateString == todayDateString && diplayedDateStrings.length > 0))
                                && jQuery.inArray(dateString, diplayedDateStrings) == -1) {

                                var d = document.createElement('div');
                                d.id = 'date_' + message.ts;
                                box.append(d);
                                $(d).hide();

                                var displayDate = document.createElement("b");
                                $(displayDate).text(dateString);
                                d.appendChild(displayDate);
                                diplayedDateStrings.push(dateString);

                                $(d).addClass('ui-chatbox-entry');
                                $(d).addClass('ui-chatbox-date');
                                $(d).css('maxWidth', $(box).width());
                                if (fadeIn) {
                                    $(d).fadeIn();
                                } else {
                                    $(d).show();
                                }
                            }


                            // Show message

                            var e = document.createElement('div');
                            e.id = 'clanChatMessage_' + message.id;
                            box.append(e);
                            $(e).hide();

                            var systemMessage = false;

                            var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
                            var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();

                            var time = document.createElement("b");
                            $(time).text("[" + hours + ":" + minutes + "] ");
                            e.appendChild(time);

                            if (message.player_name && message.type == 'message') {
                                var peerName = document.createElement("b");
                                $(peerName).text(message.player_name + ": ");
                                e.appendChild(peerName);
                            } else {
                                systemMessage = true;
                            }

                            var msgElement = document.createElement(systemMessage ? "i" : "span");
                            $(msgElement).text(message.message);

                            e.appendChild(msgElement);
                            $(e).addClass('ui-chatbox-entry');
                            $(e).addClass('ui-chatbox-' + message.type);
                            $(e).css('maxWidth', $(box).width());
                            if (fadeIn) {
                                $(e).fadeIn();
                            } else {
                                $(e).show();
                            }

                            box.chatbox('option', 'boxManager')._scrollToBottom();
                        });
                    }
                },
                displayOnlinePlayer: function(onlinePlayerString) {
                    if (onlinePlayerString !== false) {
                        $('.ui-chatbox-online').text(onlinePlayerString);
                    }
                },
                highlightBox: function() {
                    var self = this;

                    if (self.highlightLock) {
                        return;
                    }

                    self.highlightLock = true;
                    $('.chat_skull.highlight').fadeIn(600, function() {
                        $('.chat_skull.active').show();
                        $('.chat_skull.highlight').fadeOut(400, function() {
                            self.highlightLock = false;
                        });
                    });
                },
                toggleBox: function() {
                    this.elem.uiChatbox.toggle();
                    this.elem.updateDisplay();
                },
                _scrollToBottom: function() {
                    var box = this.elem.uiChatboxLog;
                    box.scrollTop(box.get(0).scrollHeight);
                }
            }
        },
        toggleContent: function(event) {
            this.uiChatboxContent.toggle();
            if (this.uiChatboxContent.is(":visible")) {
                this.uiChatboxInputBox.focus();
                this.uiChatboxLog.chatbox('option', 'boxManager')._scrollToBottom();
            }
        },
        updateDisplay: function() {
            var display = 0;
            if (this.uiChatbox.is(':visible')) {
                if (this.uiChatboxContent.is(':visible')) {
                    display = 2;
                } else {
                    display = 1;
                }
            }

            var requestData = {
                'display': display
            };

            $.getJSON('/ajax/clanchat/ajaxSetDisplay', requestData, function() {});
        },
        widget: function() {
            return this.uiChatbox
        },
        _create: function() {
            var self = this,
                options = self.options,
                title = options.title || "No Title",
            // chatbox
                uiChatbox = (self.uiChatbox = $('<div></div>'))
                    .appendTo(document.body)
                    .addClass('ui-widget ' +
                    'ui-corner-top ' +
                    'ui-chatbox'
                )
                    .attr('outline', 0)
                    .click(function() {
                        $('.chat_skull.active').fadeOut(400);
                    })
                    .focusin(function() {
                        // ui-state-highlight is not really helpful here
                        //self.uiChatbox.removeClass('ui-state-highlight');
                        self.uiChatboxTitlebar.addClass('ui-state-focus');
                    })
                    .focusout(function() {
                        self.uiChatboxTitlebar.removeClass('ui-state-focus');
                    }),
            // titlebar
                uiChatboxTitlebar = (self.uiChatboxTitlebar = $('<div></div>'))
                    .addClass('ui-widget-header ' +
                    'ui-corner-top ' +
                    'ui-chatbox-titlebar ' +
                    'ui-dialog-header' // take advantage of dialog header style
                )
                    .click(function(event) {
                        self.toggleContent(event);
                        self.updateDisplay();
                    })
                    .appendTo(uiChatbox),
                uiChatboxTitle = (self.uiChatboxTitle = $('<span></span>'))
                    .html(title)
                    .appendTo(uiChatboxTitlebar),
                uiChatboxTitlebarClose = (self.uiChatboxTitlebarClose = $('<a href="#"></a>'))
                    .addClass('ui-corner-all ' +
                    'ui-chatbox-icon '
                )
                    .attr('role', 'button')
                    .hover(function() { uiChatboxTitlebarClose.addClass('ui-state-hover'); },
                    function() { uiChatboxTitlebarClose.removeClass('ui-state-hover'); })
                    .click(function(event) {
                        uiChatbox.hide();
                        self.updateDisplay();
                        self.options.boxClosed(self.options.id);
                        return false;
                    })
                    .appendTo(uiChatboxTitlebar),
                uiChatboxTitlebarCloseText = $('<span></span>')
                    .addClass('ui-icon ' +
                    'ui-icon-closethick')
                    .appendTo(uiChatboxTitlebarClose),
            // content
                uiChatboxContent = (self.uiChatboxContent = $('<div></div>'))
                    .addClass('ui-widget-content ' +
                    'ui-chatbox-content '
                )
                    .appendTo(uiChatbox),
                uiChatboxInputBoxSkulls = (self.uiChatboxInputBox = $('<div></div>'))
                    .addClass('chat_skull')
                    .appendTo(uiChatbox),
                uiChatboxInputBoxSkullsActive = (self.uiChatboxInputBox = $('<div></div>'))
                    .addClass('chat_skull active')
                    .hide()
                    .appendTo(uiChatbox),
                uiChatboxInputBoxSkullsHighlight = (self.uiChatboxInputBox = $('<div></div>'))
                    .addClass('chat_skull highlight')
                    .hide()
                    .appendTo(uiChatbox),
                uiChatboxLog = (self.uiChatboxLog = self.element)
                    .addClass('ui-widget-content ' +
                    'ui-chatbox-log'
                )
                    .appendTo(uiChatboxContent),
                uiChatboxInput = (self.uiChatboxInput = $('<div></div>'))
                    .addClass('ui-widget-content ' +
                    'ui-chatbox-input'
                )
                    .click(function(event) {
                        // anything?
                    })
                    .appendTo(uiChatboxContent)
                ,
                uiChatboxOnline = (self.uiChatboxInputBox = $('<div>-</div>'))
                    .addClass('ui-chatbox-online')
                    .appendTo(uiChatboxInput),
                uiChatboxInputBox = (self.uiChatboxInputBox = $('<textarea></textarea>'))
                    .addClass('ui-widget-content ' +
                    'ui-chatbox-input-box ' +
                    'ui-corner-all'
                )
                    .appendTo(uiChatboxInput)
                    .keydown(function(event) {
                        if($(this).val().length > 200){
                            $(this).val($(this).val().substr(0, 200));
                        }
                        if (event.keyCode && event.keyCode == $.ui.keyCode.ENTER) {
                            msg = $.trim($(this).val());
                            if (msg.length > 0) {
                                self.options.messageSent(self.options.id, self.options.user, msg);
                            }
                            $(this).val('');
                            return false;
                        }
                    })
                    .focusin(function() {
                        uiChatboxInputBox.addClass('ui-chatbox-input-focus');
                        var box = $(this).parent().prev();
                        box.scrollTop(box.get(0).scrollHeight);
                    })
                    .focusout(function() {
                        uiChatboxInputBox.removeClass('ui-chatbox-input-focus');
                    })
                ;

            // disable selection
            uiChatboxTitlebar.find('*').add(uiChatboxTitlebar).disableSelection();

            // switch focus to input box when whatever clicked
            uiChatboxContent.children().click(function() {
                // click on any children, set focus on input box
                self.uiChatboxInputBox.focus();
            });

            self._setWidth(self.options.width);
            self._position(self.options.offset);

            self.options.boxManager.init(self);

            if (self.options.minimized) {
                uiChatboxContent.hide();
            }

            if (!self.options.hidden) {
                uiChatbox.show();
            }

            self.updateDisplay();
        },
        _setOption: function(option, value) {
            if (value != null) {
                switch (option) {
                    case "hidden":
                        if (value)
                            this.uiChatbox.hide();
                        else
                            this.uiChatbox.show();
                        break;
                    case "offset":
                        this._position(value);
                        break;
                    case "width":
                        this._setWidth(value);
                        break;
                }
            }
            $.Widget.prototype._setOption.apply(this, arguments);
        },
        _setWidth: function(width) {
            this.uiChatboxTitlebar.width(width + "px");
            this.uiChatboxLog.width(width + "px");
        },
        _position: function(offset) {
            this.uiChatbox.css("right", offset);
        }
    });
}(jQuery));
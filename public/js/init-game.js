// After DOM ready JQueries
$(document).ready(function() {
	// Create Tipped Tooltips
	Tipped.create('.tooltip', { skin: 'tiny' });
});

function insertBBCode(elementId, startTag, closeTag)
{
	var textArea = document.id(elementId);
	var scrollposBefore = textArea.scrollTop
	var pos = textArea.getSelectedRange();
	var text = textArea.get('value');
	var selectedText = textArea.getSelectedText();
	if (pos.start == pos.end)
	{
		// Bad Browsers go here (IE)
		if (typeof document.selection != 'undefined')
		{
			// Insert Code
			textArea.focus();
			var range = document.selection.createRange();
			var insText = range.text;
			range.text = startTag + insText + closeTag;
			// change cursorposi
			range = document.selection.createRange();
			if (insText.length == 0)
			{
				range.move('character', -closeTag.length);
			}
			else
			{
				range.moveStart('character', startTag.length + insText.length + closeTag.length);
			}
			range.select();
		}
		// Good Browsers go here
		else
		{
			textArea.set('value', text.substring(0, pos.start) + startTag + closeTag + text.substring(pos.end, text.length));
			textArea.setCaretPosition(pos.start + startTag.length);
		}
	}
	else
	{
		textArea.set('value', text.substring(0, pos.start) + startTag + selectedText + closeTag + text.substring(pos.end, text.length));
		textArea.setCaretPosition(pos.start + startTag.length + selectedText.length +  closeTag.length);
	}
	textArea.scrollTop = scrollposBefore;
}

function bbDropdown(elementId, dropdown, startTag, closeTag)
{
	if (dropdown.selectedIndex == 0)
	{
		return;
	}
	var param = dropdown.options[dropdown.selectedIndex].value;
	startTag = startTag.replace(/\$var/gi, param);
	insertBBCode(elementId, startTag, closeTag);
	dropdown.selectedIndex = 0;
}

var PanelOverseer = function() {
	this.panelContainers = [];
	this.outerClick();
};

PanelOverseer.prototype.outerClick = function()
{
	var self = this;

	$('body').on('click', function(e)
	{
		if (!e)
		{
			e = window.event;
		}
		for (i = 0; i < self.panelContainers.length; i++)
		{
			if (!(self.panelContainers[i].isChildOf((e.target || e.srcElement), self.panelContainers[i].container))
				&& self.panelContainers[i].panel.style.display != "none"
			) {
				self.panelContainers[i].hidePanel();
			}
		}
	});
};

PanelOverseer.prototype.closeOtherPanels = function(toggleId)
{
	for (var i = 0; i < this.panelContainers.length; i++)
	{
		if (this.panelContainers[i].id != toggleId)
		{
			this.panelContainers[i].hidePanel();
		}
	}
};

PanelOverseer.prototype.registerPanelContainer = function(containerId, closeDelay)
{
	var newPanelToggle = PanelToggle;
	newPanelToggle.initialize(this, containerId, closeDelay);
	this.panelContainers.push(newPanelToggle);
};

var PanelToggle = {
	overseer : null,
	id : 0,
	container : null,
	link : null,
	panel : null,
	delay : 0,
	timeoutId : null
};

PanelToggle.initialize = function(overseerRef, containerId, closeDelay)
{
	var self = this;
	this.overseer = overseerRef;
	this.id = containerId;
	this.container = $('#toggleContainer' + this.id);
	if (!this.container)
		return;
	this.link = $('#toggleLink' + this.id);
	this.panel = $('#togglePanel' + this.id);

	// hide panel initially
	this.hidePanel();

	this.link.on('click', function()
	{
		self.toggle();
		self.setFocus();
		return false;
	});

	// delayed panel self-closing
	if (closeDelay)
		this.delay = closeDelay;

	this.container.on('mouseout', function(e)
	{
		if (!e)
		{
			e = window.event;
		}
		// custom mouseleave event
		var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
		if (reltg == self.container || self.isChildOf(reltg, self.container))
			return;

		// hide after timeout
		if (self.delay >= 0)
		{
			self.timeoutId = setTimeout(function()
			{
				self.hidePanel();
			}, self.delay);
		}
	});

	this.container.on('mouseover', function()
	{
		if (self.timeoutId)
			clearTimeout(self.timeoutId);
	});

	var closeElement = $('#toggleClose' + this.id);
	if (!closeElement)
		closeElement = this.container;

	closeElement.on('click', function()
	{
		self.hidePanel();
	});
};

PanelToggle.isChildOf = function(child, parent)
{
	while (child && child != parent)
	{
		child = child.parentNode;
	}
	return child == parent;
};

PanelToggle.hidePanel = function()
{
	this.panel.css('display', 'none');
	this.link.className = "toggleHidden";
};

PanelToggle.toggle = function()
{
	this.panel.style.display = this.panel.style.display == "none" ? "" : "none";
	this.link.className = this.link.className == "toggleHidden" ? "" : "toggleHidden";
	this.overseer.closeOtherPanels(this.id);
};

PanelToggle.setFocus = function()
{
	var formElement = document.id('toggleForm' + this.id);
	if (formElement && formElement.elements[0] != null)
	{
		for (var i = 0; i < formElement.length; i++)
		{
			if (formElement.elements[i].type != 'hidden'
				&& !formElement.elements[i].readOnly
				&& !formElement.elements[i].disabled
			) {
				formElement.elements[i].focus();
				break;
			}
		}
	}
};
/**
 * Customizer Repeater Control Scripts
 */
(function($, api) {
	'use strict';
	
	api.controlConstructor.repeater = api.Control.extend({
		ready: function() {
			var control = this;
			
			control.initRepeater();
			control.bindEvents();
		},
		
		initRepeater: function() {
			var control = this;
			var value = control.setting.get();
			var items = [];
			
			// Parse existing value
			try {
				items = JSON.parse(value) || [];
			} catch(e) {
				items = [];
			}
			
			// Migrate old data format if needed
			if (!Array.isArray(items)) {
				items = control.migrateOldData();
			}
			
			// Render existing items
			control.renderItems(items);
			
			// Update value
			control.updateValue();
		},
		
		migrateOldData: function() {
			var control = this;
			var items = [];
			
			// Check for old footer_partner_1 and footer_partner_2
			var partner1 = api('footer_partner_1') ? api('footer_partner_1').get() : '';
			var partner2 = api('footer_partner_2') ? api('footer_partner_2').get() : '';
			
			if (partner1) {
				items.push({
					image_id: partner1,
					width: '',
					height: ''
				});
			}
			
			if (partner2) {
				items.push({
					image_id: partner2,
					width: '',
					height: ''
				});
			}
			
			return items;
		},
		
		bindEvents: function() {
			var control = this;
			var container = control.container;
			
			// Add button
			container.on('click', '[data-repeater-add]', function(e) {
				e.preventDefault();
				control.addItem();
			});
			
			// Remove button
			container.on('click', '[data-repeater-remove]', function(e) {
				e.preventDefault();
				var item = $(this).closest('[data-repeater-item]');
				control.removeItem(item);
			});
			
			// Toggle item
			container.on('click', '[data-repeater-toggle]', function(e) {
				e.preventDefault();
				var item = $(this).closest('[data-repeater-item]');
				control.toggleItem(item);
			});
			
			// Field changes
			container.on('change keyup', '.repeater-input', function() {
				control.updateValue();
				control.updatePreview();
			});
			
			// Media selection
			container.on('click', '[data-media-select]', function(e) {
				e.preventDefault();
				var index = $(this).data('media-select');
				control.selectMedia(index);
			});
			
			// Media removal
			container.on('click', '[data-media-remove]', function(e) {
				e.preventDefault();
				var index = $(this).data('media-remove');
				control.removeMedia(index);
			});
			
			// Make items sortable
			control.makeSortable();
		},
		
		renderItems: function(items) {
			var control = this;
			var container = control.container.find('[data-repeater-items]');
			var template = $('#repeater-item-template-' + control.id);
			
			container.empty();
			
			items.forEach(function(item, index) {
				control.renderItem(item, index);
			});
		},
		
		renderItem: function(data, index) {
			var control = this;
			var container = control.container.find('[data-repeater-items]');
			var template = $('#repeater-item-template-' + control.id).html();
			
			// Default values
			data = $.extend({
				image_id: '',
				width: '',
				height: '',
				width_unit: 'rem',
				height_unit: 'rem'
			}, data);
			
			// Get image URL if ID exists
			var imageUrl = '';
			var imageStyle = 'display:none;';
			var removeStyle = 'display:none;';
			
			if (data.image_id) {
				var attachment = wp.media.attachment(data.image_id);
				attachment.fetch().then(function() {
					var url = attachment.get('url');
					var preview = container.find('[data-media-preview="' + index + '"] img');
					preview.attr('src', url).show();
					container.find('[data-media-remove="' + index + '"]').show();
				});
				removeStyle = '';
			}
			
			// Replace template variables
			template = template.replace(/{{index}}/g, index);
			template = template.replace(/{{itemNumber}}/g, index + 1);
			template = template.replace(/{{image_id}}/g, data.image_id);
			template = template.replace(/{{width}}/g, data.width);
			template = template.replace(/{{height}}/g, data.height);
			template = template.replace(/{{imageUrl}}/g, imageUrl);
			template = template.replace(/{{imageStyle}}/g, imageStyle);
			template = template.replace(/{{removeStyle}}/g, removeStyle);
			
			// Handle unit selections
			template = template.replace(/{{width_unit_rem}}/g, data.width_unit === 'rem' ? 'selected' : '');
			template = template.replace(/{{width_unit_px}}/g, data.width_unit === 'px' ? 'selected' : '');
			template = template.replace(/{{width_unit_percent}}/g, data.width_unit === '%' ? 'selected' : '');
			template = template.replace(/{{width_unit_auto}}/g, data.width_unit === 'auto' ? 'selected' : '');
			
			template = template.replace(/{{height_unit_rem}}/g, data.height_unit === 'rem' ? 'selected' : '');
			template = template.replace(/{{height_unit_px}}/g, data.height_unit === 'px' ? 'selected' : '');
			template = template.replace(/{{height_unit_percent}}/g, data.height_unit === '%' ? 'selected' : '');
			template = template.replace(/{{height_unit_auto}}/g, data.height_unit === 'auto' ? 'selected' : '');
			
			container.append(template);
		},
		
		addItem: function() {
			var control = this;
			var container = control.container.find('[data-repeater-items]');
			var items = control.getItems();
			
			// Add new empty item
			var newItem = {
				image_id: '',
				width: '',
				height: '',
				width_unit: 'rem',
				height_unit: 'rem'
			};
			
			control.renderItem(newItem, items.length);
			control.updateValue();
			control.updatePreview();
		},
		
		removeItem: function(item) {
			var control = this;
			
			item.fadeOut(200, function() {
				item.remove();
				control.reindexItems();
				control.updateValue();
				control.updatePreview();
			});
		},
		
		toggleItem: function(item) {
			var content = item.find('.repeater-item-content');
			var icon = item.find('[data-repeater-toggle] .dashicons');
			
			content.slideToggle(200);
			icon.toggleClass('dashicons-arrow-down dashicons-arrow-up');
		},
		
		selectMedia: function(index) {
			var control = this;
			var frame;
			
			// Create media frame
			frame = wp.media({
				title: 'Select Partner Logo',
				button: {
					text: 'Use this image'
				},
				multiple: false
			});
			
			// Handle selection
			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				var container = control.container;
				
				// Update hidden input
				container.find('[data-repeater-item][data-index="' + index + '"] .repeater-media-id').val(attachment.id);
				
				// Update preview
				container.find('[data-media-preview="' + index + '"] img').attr('src', attachment.url).attr('data-image-id', attachment.id).show();
				container.find('[data-media-remove="' + index + '"]').show();
				
				// Update value and preview
				control.updateValue();
				control.updatePreview();
			});
			
			frame.open();
		},
		
		removeMedia: function(index) {
			var control = this;
			var container = control.container;
			
			// Clear hidden input
			container.find('[data-repeater-item][data-index="' + index + '"] .repeater-media-id').val('');
			
			// Hide preview
			container.find('[data-media-preview="' + index + '"] img').hide();
			container.find('[data-media-remove="' + index + '"]').hide();
			
			// Update value and preview
			control.updateValue();
			control.updatePreview();
		},
		
		makeSortable: function() {
			var control = this;
			var container = control.container.find('[data-repeater-items]');
			
			if ($.fn.sortable) {
				container.sortable({
					items: '[data-repeater-item]',
					handle: '.repeater-item-header',
					axis: 'y',
					update: function() {
						control.reindexItems();
						control.updateValue();
						control.updatePreview();
					}
				});
			}
		},
		
		reindexItems: function() {
			var control = this;
			var container = control.container;
			
			container.find('[data-repeater-item]').each(function(index) {
				var item = $(this);
				
				// Update data-index
				item.attr('data-index', index);
				
				// Update item number display
				item.find('.item-number').text(index + 1);
				
				// Update media button data attributes
				item.find('[data-media-select]').attr('data-media-select', index);
				item.find('[data-media-remove]').attr('data-media-remove', index);
				item.find('[data-media-preview]').attr('data-media-preview', index);
			});
		},
		
		getItems: function() {
			var control = this;
			var container = control.container;
			var items = [];
			
			container.find('[data-repeater-item]').each(function() {
				var item = $(this);
				var data = {};
				
				item.find('.repeater-input, .repeater-media-id').each(function() {
					var field = $(this).data('field');
					var value = $(this).val();
					
					if (field) {
						data[field] = value;
					}
				});
				
				items.push(data);
			});
			
			return items;
		},
		
		updateValue: function() {
			var control = this;
			var items = control.getItems();
			var value = JSON.stringify(items);
			
			control.setting.set(value);
			
			// Trigger change for preview update
			control.container.find('[data-repeater-value]').val(value).trigger('change');
		},
		
		updatePreview: function() {
			var control = this;
			
			// Trigger the main customizer preview update
			if (control.setting) {
				control.setting.callbacks.fireWith(control.setting, [control.setting.get()]);
			}
		}
	});
	
})(jQuery, wp.customize);
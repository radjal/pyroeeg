(function ($) {
	$(function () {

		// generate a slug when the user types a title in
		pyro.generate_slug('#carte-content-tab input[name="title"]', '#carte-content-tab input[name="slug"]');

		// needed so that Keywords can return empty JSON
		$.ajaxSetup({
			allowEmpty: true
		});

		$('#keywords').tagsInput({
			autocomplete_url: SITE_URL + 'admin/keywords/autocomplete'
		});

		// editor switcher
		$('select[name^=type]').live('change', function () {
			var chunk = $(this).closest('li.editor');
			var textarea = $('textarea', chunk);

			// Destroy existing WYSIWYG instance
			if (textarea.hasClass('wysiwyg-simple') || textarea.hasClass('wysiwyg-advanced')) {
				textarea.removeClass('wysiwyg-simple');
				textarea.removeClass('wysiwyg-advanced');

				var instance = CKEDITOR.instances[textarea.attr('id')];
				instance && instance.destroy();
			}
			// Set up the new instance
			textarea.addClass(this.value);
			pyro.init_ckeditor();
		});

		$(document.getElementById('carte-options-tab')).find('ul').find('li').first().find('a').colorbox({
			srollable: false,
			innerWidth: 600,
			innerHeight: 280,
			href: SITE_URL + 'admin/carte/categories/create_ajax',
			onComplete: function () {
				$.colorbox.resize();
				var $form_categories = $('form#categories');
				$form_categories.removeAttr('action');
				$form_categories.live('submit', function (e) {

					var form_data = $(this).serialize();

					$.ajax({
						url: SITE_URL + 'admin/carte/categories/create_ajax',
						type: "POST",
						data: form_data,
						success: function (obj) {

							if (obj.status == 'ok') {

								//succesfull db insert do this stuff
								var $select = $('select[name=category_id]');
								//append to dropdown the new option
								$select.append('<option value="' + obj.category_id + '" selected="selected">' + obj.title + '</option>');
								$select.trigger("liszt:updated");
								// TODO work this out? //uniform workaround
								$(document.getElementById('carte-options-tab')).find('li').first().find('span').html(obj.title);

								//close the colorbox
								$.colorbox.close();
							} else {
								//no dice

								//append the message to the dom
								var $cboxLoadedContent = $(document.getElementById('cboxLoadedContent'));
								$cboxLoadedContent.html(obj.message + obj.form);
								$cboxLoadedContent.find('p').first().addClass('notification error').show();
							}
						}
					});
					e.preventDefault();
				});

			}
		});
	});
})(jQuery);


// datepicker localisation in French
 $.datepicker.regional["fr"] = {
    clearText: "Effacer", clearStatus: "",
    closeText: "Fermer", closeStatus: "Fermer sans modifier",
    prevText: "&lt;Préc", prevStatus: "Voir le mois précédent",
    nextText: "Suiv&gt;", nextStatus: "Voir le mois suivant",
    currentText: "Courant", currentStatus: "Voir le mois courant",
    monthNames: ["Janvier","Février","Mars","Avril","Mai","Juin",
    "Juillet","Août","Septembre","Octobre","Novembre","Décembre"],
    monthNamesShort: ["Jan","Fév","Mar","Avr","Mai","Jun",
    "Jul","Aoû","Sep","Oct","Nov","Déc"],
    monthStatus: "Voir un autre mois", yearStatus: "Voir un autre année",
    weekHeader: "Sm", weekStatus: "",
    dayNames: ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"],
    dayNamesShort: ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],
    dayNamesMin: ["Di","Lu","Ma","Me","Je","Ve","Sa"],
    dayStatus: "Utiliser DD comme premier jour de la semaine", dateStatus: "Choisir le DD, MM d",
    dateFormat: "dd/mm/yy", firstDay: 1, 
    initStatus: "Choisir la date", isRTL: false};
$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );

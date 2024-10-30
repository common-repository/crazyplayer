(function() {
	tinymce.PluginManager.add('crazyplayer_mce_button', function( editor, url ) {
		editor.addButton( 'crazyplayer_mce_button', {
			text: 'CrazyPlayer',
			icon: false,
			type: 'menubutton',
			menu: [
				{
					text: 'Single Player',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Configure your Player',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'playerid',
                                    label: 'Player ID',
                                },
                                {
                                    type: 'textbox',
                                    name: 'title',
                                    label: 'Music title',
                                },
                                {
                                    type: 'textbox',
                                    name: 'artist',
                                    label: 'Singer name',
                                },
                                {
                                    type: 'textbox',
                                    name: 'url',
                                    label: 'MP3 URL',
                                },
                                {
                                    type: 'textbox',
                                    name: 'thumb',
                                    label: 'Thumbnail',
                                },
                                {
                                    type: 'textbox',
                                    name: 'theme',
                                    label: 'Theme Color',
                                    value: '#cf3c3f',
                                },
                                {
                                    type: 'textbox',
                                    name: 'bgcolor',
                                    label: 'Background Color',
                                    value: '#1e2226',
                                },
                                {
                                    type: 'listbox',
                                    name: 'autoplay',
                                    label: 'Autoplay',
                                    'values': [
				                        {text: 'True', value: 'true'},
								        {text: 'False', value: 'false'}
								    ]
                                },                                
                            ],
                            onsubmit: function( e ) {
                                editor.insertContent( '[crazyplayer_narrow id="' + e.data.playerid + '" title="' + e.data.title + '" artist="' + e.data.artist + '" url="' + e.data.url + '" thumb="' + e.data.thumb + '" theme="' + e.data.theme + '" bgcolor="' + e.data.bgcolor + '" autoplay="' + e.data.autoplay + '"]');
                            }
                        });
                    }
				},
				{
					text: 'Player with playlist',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Configure your Player',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'playlistid',
                                    label: 'Type playlist ID',
                                },
                                {
                                    type: 'textbox',
                                    name: 'theme',
                                    label: 'Theme Color',
                                    value: '#cf3c3f',
                                },
                                {
                                    type: 'textbox',
                                    name: 'bgcolor',
                                    label: 'Background Color',
                                    value: '#1e2226',
                                },
                                {
                                    type: 'listbox',
                                    name: 'style',
                                    label: 'Style',
                                    'values': [
				                        {text: 'Style 1', value: 'style1'},
								        {text: 'Style 2', value: 'style2'}
								    ]
                                },
                                {
                                    type: 'listbox',
                                    name: 'autoplay',
                                    label: 'Autoplay',
                                    'values': [
				                        {text: 'False', value: 'false'},
								        {text: 'True', value: 'true'}
								    ]
                                }, 
                                {
                                    type: 'listbox',
                                    name: 'lyric',
                                    label: 'Show Lyric?',
                                    'values': [
				                        {text: 'No', value: 'false'},
								        {text: 'Yes', value: 'true'}
								    ]
                                },                               
                            ],
                            onsubmit: function( e ) {
                                editor.insertContent( '[crazyplayer id="' + e.data.playlistid + '" theme="' + e.data.theme + '" bgcolor="' + e.data.bgcolor + '" style="' + e.data.style + '" autoplay="' + e.data.autoplay + '" showlrc="' + e.data.lyric + '"]');
                            }
                        });
                    }
				}
			]
		});
	});
})();
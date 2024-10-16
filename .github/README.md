## cb-aberforth2024

#### [Chillibyte](https://www.chillibyte.co.uk)


# Utility Functions

##	parse_phone( $phone )
input: phone number string
output: +44 prefixed phone string suitable for use in tel: anchors
##	get_vimeo_data_from_id( $video_id, $data )
TBC
##	cb_gutenberg_admin_styles()
Hooked into admin_head, this increases the width of the Gutenberg editor.
##	cb_disable_editor_fullscreen_by_default()
Does what it says on the tin. Called by enqueue_block_editor_assets hook.
##	cb_social_share( $id )
Passed a post ID, returns a list of share icons for Twitter, LinkedIn and Facebook.
##	cb_list( $field )
Passed a multi-line HTML string with line-breaks, returns each line as a list item.
##	estimate_reading_time_in_minutes( $content, $wpm, $gutenberg, $formatted )
Parses $content and estimates read time at a rate of $wpm words per minute. If $gutenberg is true, it will parse blocks, else it works on the raw $content provided. If $formatted is true, returns a paragraph with the ‘reading’ class, containing the string “Estimated reading time n minute(s)”, pluralised by the pluralise() function.

# Utility Shortcodes

## 2.1	[contact_address]
Returns content of contact_address field in Site-Wide Settings
## 2.2	[contact_phone]
Returns tel: anchor with parsed phone number specified in Site-Wide Settings as the link and the unparsed content as the link text.
## 2.3	[contact_email]
Returns mailto: anchor with email address specified in Site-Wide Settings as the link and text.
## 2.4	[contact_email_icon]
Returns mailto: anchor with email address specified in Site-Wide Settings as the link and the Font Awesome envelope icon.
## 2.5	[social_xx_icon]
Available for Facebook (fb), Instagram (ig), Twitter (tw), Pinterest (pt), YouTube (yt), LinkedIn (in) and generic ‘Google’ (gp).
Returns anchor with link specified in Site-Wide Settings with the relevant Font Awesome icon.


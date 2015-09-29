<h2 id="page_title">{{ helper:lang line="carte:archive_title" }}</h2>
<h3>{{ month_year }}</h3>

{{ if posts }}

	{{ posts }}

		<div class="post">

			<h3><a href="{{ url }}">{{ title }}</a></h3>

			<div class="meta">

			<div class="date">
				{{ helper:lang line="carte:posted_label" }}
				<span>{{ helper:date timestamp=created_on }}</span>
			</div>

			{{ if category }}
			<div class="category">
				{{ helper:lang line="carte:category_label" }}
				<span><a href="{{ url:site }}carte/category/{{ category:slug }}">{{ category:title }}</a></span>
			</div>
			{{ endif }}

			{{ if keywords }}
			<div class="keywords">
				{{ keywords }}
					<span><a href="{{ url:site }}carte/tagged/{{ keyword }}">{{ keyword }}</a></span>
				{{ /keywords }}
			</div>
			{{ endif }}

			</div>

			<div class="preview">
			{{ preview }}
			</div>

			<p><a href="{{ url }}">{{ helper:lang line="carte:read_more_label" }}</a></p>

		</div>

	{{ /posts }}

	{{ pagination }}

{{ else }}
	
	{{ helper:lang line="carte:currently_no_posts" }}

{{ endif }}
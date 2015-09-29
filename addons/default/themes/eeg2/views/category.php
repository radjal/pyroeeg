<div id="carte-catalogue" class="firesale width-threefourth right last ">

  <section id="listing-header">

              <div class="left">
            <a href="{{ firesale:url route="category-custom" }}style/grid" class="grid{{ if layout == 'grid' }} selected{{ endif }}"><span class="icon"></span><?php echo lang('firesale:categories:grid'); ?></a>
            <a href="{{ firesale:url route="category-custom" }}style/list" class="list{{ if layout == 'list' }} selected{{ endif }}"><span class="icon"></span><?php echo lang('firesale:categories:list'); ?></a>
          </div>

          <div class="right">
            <div id="listing-sort" class="switcher">
              <span>{{ order.title }}</span>
              <ul>
{{ ordering }}
                <li><a href="{{ firesale:url route="category-custom" }}order/{{ key }}">{{ title }}</a></li>
{{ /ordering }}
              </ul>
            </div>
          </div>

          <br class="clear" />
  </section>

        {{ pagination.links }}

        <section id="listing" class="{{ layout }}">

{{ if products }}
{{ products }}
          <article>


            <!--<section class="price-round medium"><span class="rrp">{{ if rrp > price }}{{ rrp }}{{ endif }}</span><span class="price">{{ price }}</span></section>-->
            <header>
              <h3>{{ title }}<em>{{ code }}</em>              </h3>
            </header>
            <p class="description">{{ helper:substr string=description start="0" end="250" }}...</p>
            <footer>
            </footer>
            <br class="clear" />
          </article>

{{ /products }}
{{ else }}
          <center style="margin-top: 135px"><h3><?php echo lang('firesale:prod_none'); ?></h3></center>
{{ endif }}

          <br class="clear" />
        </section>

        {{ pagination.links }}

      </div>

      <br class="clear" />


      <div class=" firesale product">
        
        <section class="product-details">
          <ul>
            <li class="availability"><strong><?php echo lang('firesale:product:label_availability'); ?></strong>{{ product.stock_status.value }} ({{ product.stock }})</li>
            <li class="model"><strong><?php echo lang('firesale:product:label_model'); ?></strong>{{ product.title }}</li>
            <li class="prodid"><strong><?php echo lang('firesale:product:label_product_code'); ?></strong><span><?php echo $product['code']; ?></span></li>
          </ul>
          <section class="price-round large"><span class="rrp">{{ if product.rrp > product.price }}{{ product.rrp_formatted }}{{ endif }}</span><span class="price">{{ product.price_formatted }}</span></section>

        <br class="clear" />
      </div>

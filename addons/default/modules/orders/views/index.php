<div class="content">

<h2>{{ template:title }}</h2>

{{ if orders.total > 0 }}
<div id="order">
    <h3>{{ helper:lang line="order:questions" }}</h3>
    {{ pagination:links }}
    <div id="questions">
        <ol>
            {{ orders.entries }}
            <li>{{ url:anchor segments="order/#{{ id }}" title="{{ question }}" class="question" }}</li>
            {{ /orders.entries }}
        </ol>
    </div>
    <div id="answers">
        <h3>{{ helper:lang line="order:answers" }}</h3>
        <ol> 
            {{ orders.entries }}
            <li class="answer">
                <h4 id="{{ id }}">{{ question }}</h4>
                <p>{{ answer }}</p>
            </li>
            {{ /orders.entries }}
        </ol>
    </div>
</div>
{{ else }}
<h4>{{ helper:lang line="order:no_orders" }}</h4>
{{ endif }}

</div>

<?php 
//var_dump($orders) ; 
?>
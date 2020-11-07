## About Challange
<p>Write a program that can price a cart of products, accept multiple products, combine offers, and display a total detailed bill in different currencies (based on user selection).</p>

## Structure And Design Pattern 
<ul>
	<li>Laravel Farmework used on this application </li>
	<li><strong>HMVC</strong>  Architecture Pattern</li>
	<li><strong>Repository</strong> Pattern</li>
	<li><strong>Strategy</strong> Pattern for discounts </li> 
</ul>

<p>
	<strong> This My source web site On Currency Convert <a href="https://usd.mconvert.net"> https://usd.mconvert.net</a>< i use <strong> scraping </strong> to get the converted values and it details like name symbol then calculate the ration on this date to get order price as requested on this date</strong> after scrapping the data returned like the flowing code
<p>
	<p> 
		Convert <strong> 1 USD To EGP </strong>
	<code> [
		  "ratio" => 15.69,
		  "from" => [
		    "value" => 1.0,
		    "symbol" => "$",
		    "name" => "US Dollar",
		  ],
		  "converted" => [
		    "value" => 15.69,
		    "symbol" => "£",
		    "name" => "Egyptian Pound",
		  ]
		]
</code>
</p>

## Installation

<p> clone project 
	<ul>
		<li>create database </li>
		<li>duplicat .env.example create your .env file</li>
		<li> put your configuration like db name password</li>
		<li> <strong> run composer install </strong> </li>
		<li> <strong> php artisan key:generate </strong> </li>
		<li> <strong> php artisan module:migrate </strong> </li>
		<li> <strong> php artisan module:seed </strong> </li>
		<li> <strong> ./vendor/bin/phpunit </strong> test should pass correctly</li>
		<li> <strong> php artisan serve </strong> </li>
	</ul>

<p> send post request to the follwong url<strong> like  <a>http://127.0.0.1:8000/api/orders/store</a> </strong>  </p>

## Request
<code>
	{
	    "cart": [
	        "T-shirt",
	        "T-shirt",
	        "Shoes",
	        "Jacket"
	    ],
	    "currency": "EGP"
	}	
</code>

## Response
<code>
	{
    "data": {
        "Subtotal": "£1050.6024",
        "Taxes": "£147.084336",
        "discount": [
            {
                "name": "Jacket",
                "discount_off": "£156.82155",
                "discount_amount": "50%"
            },
            {
                "name": "Shoes",
                "discount_off": "£39.20931",
                "discount_amount": "10%"
            }
        ],
        "total": "£1001.655876"
    }
}
</code>

## Warning

<p> <strong>shoes</strong> and <strong>Shoes</strong> Laravel is insensitive not case sensitive  on <strong>exists</strong> so i handle it from code and this product will be ignored and not calculated to save time i detect this issue later as it is <strong>  database issue </strong></p>


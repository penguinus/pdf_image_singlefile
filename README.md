Make one POST request that:
Body:
- “pdf_url” : Url to pdf, use this url for testing.
  (https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf)
- “signature”: Image file (use any image you want)
  Response:
  Returns the pdf file from the url with the image in it.

Implementation
==============
Simple quick implementation in standalone PHP file. 
In real project using some MVC framework is recommended.

Installation
============
1. git clone 
2. composer install
3. php -S localhost:8080
4. Send POST request to http://localhost:8080 (GET request will show sample form)
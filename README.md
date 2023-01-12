Make one POST request that:
Body:
- “pdf_url” : Url to pdf, use this url for testing.
  (https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf)
- “signature”: Image file (use any image you want)
  Response:
  Returns the pdf file from the url with the image in it.

Implementation
==============
Simple quick implementation in standalone PHP file. In real project using some MVC framework is recommended.
Uses FPDF and FPDI for PDF manipulations, 
GD is used for image manipulations. Possible image formats are GIF, JPEG, PNG. 
Expected image size is 150px x 110px, image will be resized to that size automatically.
Temporary files are saved to the same directory with index.php. 
Changing it to some safe path is a point for todo. 


Installation
============
1. git clone 
2. composer install
3. php -S localhost:8080
4. Send POST request to http://localhost:8080 (GET request will show sample form)
# CSE 310 – Module 2: Data Analysis WordPress Plugin

This project is a custom WordPress plugin developed in PHP for CSE 310 – Applied Programming (Module 2: Data Analysis).
The plugin loads a CSV dataset containing shipment records and performs real data analysis operations directly inside the WordPress admin dashboard.
The project demonstrates how data analysis concepts such as filtering, sorting, aggregation, and grouping can be applied within a real web-based platform.

## Instructions for Build and Use

[Software Demo – YouTube Video](https://youtu.be/E4m5kOaUkb4)

Steps to build and/or run the software:

1. Install WordPress locally (LocalWP was used in this project).
2. Copy the folder `cse310-data-analysis` into:
   wp-content/plugins/
3. Log in to the WordPress admin dashboard.
4. Go to Plugins → Activate “CSE310 Data Analysis”.
5. Click on the “Data Analysis” menu item in the admin panel.

Instructions for using the software:

1. The plugin automatically loads the dataset from `data/shipments.csv`.
2. Use the dropdown filters to filter shipments by:
   - Origin City
   - Status
3. The dashboard dynamically updates:
   - Total Cost (SUM aggregation)
   - Average Cost (AVG aggregation)
   - Top 3 Destinations by Total Cost (GROUP BY logic)
4. Filtering is implemented using `array_filter`.
5. Sorting is implemented using `usort` with the spaceship operator.
6. Aggregation is implemented using `array_reduce`.

## Development Environment

To recreate the development environment, you need the following software and/or libraries with the specified versions:

* WordPress (latest version)
* LocalWP (local development environment)
* PHP (bundled with LocalWP)
* Visual Studio Code
* Git
* GitHub

The plugin was developed using an object-oriented and modular architecture.

## Useful Websites to Learn More

I found these websites useful in developing this software:

* WordPress Developer Documentation – https://developer.wordpress.org/
* PHP Manual – https://www.php.net/manual/en/
* array_reduce() Documentation – https://www.php.net/manual/en/function.array-reduce.php
* usort() Documentation – https://www.php.net/manual/en/function.usort.php
* Markdown Guide – https://www.markdownguide.org/

## Future Work

The following items I plan to fix, improve, and/or add to this project in the future:

* [ ] Add graphical visualization (charts) of aggregated data
* [ ] Add date range filtering
* [ ] Improve UI styling using custom CSS instead of inline styles
* [ ] Allow CSV upload directly from the admin interface
* [ ] Implement pagination for large datasets

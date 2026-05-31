# Advertising Bid Auction

## Overview

This application implements a mechanism for advertising bids. It processes a CSV file containing ad bids and returns the best ad to display along with the second-best bid amount. This is commonly used in online advertising platforms where the winner pays the second-highest bid.

&nbsp;

## Project Structure

```
Apps/
  └ AdvertisingBidAuction/
    ├── public/
    └────── index.php       # entry point
```

&nbsp;

## How It Works

The application:
1. Reads a CSV file with ad information (ad_id and bid amount)
2. Validates each row according to configured rules
3. Identifies the ad with the highest bid
4. Returns both the winning ad ID and the second-best bid amount

**Example:**
- Input: {ads}.csv with bids [1: 100, 2: 250, 3: 150, 4: 200]
- Output: Best ad ID and second-best bid: {2: 200}

## Requirements
- CSV file with columns: `ad_id` and `bid`

&nbsp;

## Running the Application

Navigate to the application's public directory and execute the command:

```
cd Apps/AdvertisingBidAuction/public
php index.php best-ad-second-bid <file> [options]
```

## Parameters

`<file>`: Path to the CSV file
- Can be a relative path (file will be searched in the public directory)
- Can be a full path to the file

## Options

`--continue`: Continue processing when invalid rows are encountered
- Invalid rows will be skipped and excluded from the analysis
- Without this flag, the program stops at the first error

`--print-warnings`: Print detailed warnings for invalid rows
- Displays information about each invalid row and the reason for validation failure
- Useful for debugging CSV file issues

&nbsp;

## Examples

Basic usage with a file in the public directory. For full path do not forget the ""s:
```
php index.php best-ad-second-bid "ads.csv"
```

Process with error handling and warnings:
```
php index.php best-ad-second-bid ads.csv --continue --print-warnings
```


&nbsp;

## Configuration

### Application Settings

Modify application-specific settings in core/config/app.php:
- Production mode
- Template paths
- Application-specific constants

### Commands

Add or modify available commands in core/config/commands.php:
- Command class mappings
- Command dependencies
- Command-specific configurations

### Validations

Control row validation rules in core/config/validations.php:
- CSV format validation
- Ad ID validation rules
- Bid amount validation rules
- Custom validator chains

&nbsp;
&nbsp;
## CSV File Format

Your CSV file should contain the following columns:

Requirements:
- First row must contain column headers: ad_id and bid
- ad_id: Unique identifier for the advertisement
- bid: Numeric bid amount (must be a valid number)

## Output

The application outputs:
1. Best Ad ID: The ID of the ad with the highest bid
2. Second-Best Bid: The monetary amount of the second-highest bid

&nbsp;

For more details about the framework architecture, refer to the [main README](/).

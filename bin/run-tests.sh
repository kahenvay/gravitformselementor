#!/bin/bash

# Test runner script for Gravity Form Elementor Widget
# This script provides an easy way to run different types of tests

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Default values
TEST_TYPE="all"
COVERAGE=false
VERBOSE=false
SETUP_WP=false

# Function to display usage
usage() {
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -t, --type TYPE     Test type: all, unit, integration (default: all)"
    echo "  -c, --coverage      Generate coverage report"
    echo "  -v, --verbose       Verbose output"
    echo "  -s, --setup-wp      Set up WordPress test environment"
    echo "  -h, --help          Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0                  # Run all tests"
    echo "  $0 -t unit          # Run only unit tests"
    echo "  $0 -t integration   # Run only integration tests"
    echo "  $0 -c               # Run all tests with coverage"
    echo "  $0 -s               # Set up WordPress test environment"
}

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Function to check if composer is installed
check_composer() {
    if ! command -v composer &> /dev/null; then
        print_error "Composer is not installed. Please install Composer first."
        exit 1
    fi
}

# Function to check if dependencies are installed
check_dependencies() {
    if [ ! -d "vendor" ]; then
        print_status "Installing Composer dependencies..."
        composer install
    fi
}

# Function to set up WordPress test environment
setup_wordpress() {
    print_status "Setting up WordPress test environment..."
    
    # Default database settings
    DB_NAME=${DB_NAME:-wordpress_test}
    DB_USER=${DB_USER:-root}
    DB_PASS=${DB_PASS:-}
    DB_HOST=${DB_HOST:-localhost}
    WP_VERSION=${WP_VERSION:-latest}
    
    print_status "Using database: $DB_NAME"
    print_status "Using user: $DB_USER"
    print_status "Using host: $DB_HOST"
    print_status "Using WordPress version: $WP_VERSION"
    
    if [ -f "bin/install-wp-tests.sh" ]; then
        bash bin/install-wp-tests.sh "$DB_NAME" "$DB_USER" "$DB_PASS" "$DB_HOST" "$WP_VERSION"
        print_success "WordPress test environment set up successfully"
    else
        print_error "WordPress test setup script not found"
        exit 1
    fi
}

# Function to run unit tests
run_unit_tests() {
    print_status "Running unit tests..."
    
    if [ "$COVERAGE" = true ]; then
        vendor/bin/phpunit --testsuite="Unit Tests" --coverage-html tests/coverage/html --coverage-clover tests/coverage/clover.xml
    else
        vendor/bin/phpunit --testsuite="Unit Tests"
    fi
}

# Function to run integration tests
run_integration_tests() {
    print_status "Running integration tests..."
    
    # Check if WordPress test environment is set up
    if [ ! -d "/tmp/wordpress-tests-lib" ] && [ ! -d "$WP_TESTS_DIR" ]; then
        print_warning "WordPress test environment not found. Setting it up..."
        setup_wordpress
    fi
    
    if [ "$COVERAGE" = true ]; then
        vendor/bin/phpunit --testsuite="Integration Tests" --coverage-html tests/coverage/html --coverage-clover tests/coverage/clover.xml
    else
        vendor/bin/phpunit --testsuite="Integration Tests"
    fi
}

# Function to run all tests
run_all_tests() {
    print_status "Running all tests..."
    
    # Check if WordPress test environment is set up for integration tests
    if [ ! -d "/tmp/wordpress-tests-lib" ] && [ ! -d "$WP_TESTS_DIR" ]; then
        print_warning "WordPress test environment not found. Setting it up..."
        setup_wordpress
    fi
    
    if [ "$COVERAGE" = true ]; then
        vendor/bin/phpunit --coverage-html tests/coverage/html --coverage-clover tests/coverage/clover.xml
    else
        vendor/bin/phpunit
    fi
}

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -t|--type)
            TEST_TYPE="$2"
            shift 2
            ;;
        -c|--coverage)
            COVERAGE=true
            shift
            ;;
        -v|--verbose)
            VERBOSE=true
            shift
            ;;
        -s|--setup-wp)
            SETUP_WP=true
            shift
            ;;
        -h|--help)
            usage
            exit 0
            ;;
        *)
            print_error "Unknown option: $1"
            usage
            exit 1
            ;;
    esac
done

# Main execution
print_status "Starting test runner for Gravity Form Elementor Widget"

# Check prerequisites
check_composer
check_dependencies

# Set up WordPress if requested
if [ "$SETUP_WP" = true ]; then
    setup_wordpress
    exit 0
fi

# Run tests based on type
case $TEST_TYPE in
    "unit")
        run_unit_tests
        ;;
    "integration")
        run_integration_tests
        ;;
    "all")
        run_all_tests
        ;;
    *)
        print_error "Invalid test type: $TEST_TYPE"
        print_error "Valid types are: all, unit, integration"
        exit 1
        ;;
esac

# Show coverage report location if generated
if [ "$COVERAGE" = true ]; then
    print_success "Coverage report generated at: tests/coverage/html/index.html"
fi

print_success "Tests completed successfully!"

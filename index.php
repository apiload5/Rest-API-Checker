<?php
$page_title = "Free Online API Testing Tool | Postman Alternative";
$page_description = "Test APIs online without installation. Free REST API tester with support for GET, POST, PUT, DELETE requests. No registration required.";
$page_keywords = "API testing, REST API, POSTMAN alternative, online API tester, HTTP requests";
$canonical_url = "https://apitester.yourdomain.com/";

include 'components/header.php';
include 'components/seo-meta.php';
?>

<!-- Structured Data for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Online API Testing Tool",
  "description": "Free online tool to test REST APIs with full HTTP methods support",
  "url": "<?php echo $canonical_url; ?>",
  "applicationCategory": "DeveloperTools",
  "operatingSystem": "Any",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "author": {
    "@type": "Organization",
    "name": "APITester"
  }
}
</script>

<!-- Hero Section -->
<section class="hero">
    <h1>Free Online API Testing Tool</h1>
    <p>Test REST APIs directly from your browser. No installation, no backend required.</p>
    
    <!-- Tool Statistics for SEO -->
    <div class="stats">
        <div class="stat">
            <span class="number">10,000+</span>
            <span class="label">APIs Tested</span>
        </div>
        <div class="stat">
            <span class="number">100%</span>
            <span class="label">Free</span>
        </div>
        <div class="stat">
            <span class="number">0</span>
            <span class="label">Registration</span>
        </div>
    </div>
</section>

<!-- Main Tool Container -->
<div id="app">
    <div class="tool-container">
        <!-- Request Builder -->
        <div class="request-builder">
            <div class="method-selector">
                <select id="method">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                    <option value="PATCH">PATCH</option>
                </select>
                <input type="url" id="url" placeholder="https://api.example.com/endpoint">
                <button onclick="sendRequest()">Send</button>
            </div>
            
            <!-- Tabs for different sections -->
            <div class="tabs">
                <button class="tab active" onclick="switchTab('params')">Params</button>
                <button class="tab" onclick="switchTab('headers')">Headers</button>
                <button class="tab" onclick="switchTab('body')">Body</button>
                <button class="tab" onclick="switchTab('auth')">Auth</button>
            </div>
            
            <!-- Tab Contents -->
            <div id="params-tab" class="tab-content active">
                <!-- Query parameters UI -->
            </div>
            
            <div id="body-tab" class="tab-content">
                <div class="body-editor">
                    <select id="body-type">
                        <option value="json">JSON</option>
                        <option value="form">Form Data</option>
                        <option value="text">Raw Text</option>
                    </select>
                    <textarea id="body-content" placeholder='{"key": "value"}'></textarea>
                </div>
            </div>
        </div>
        
        <!-- Response Viewer -->
        <div class="response-viewer">
            <div class="response-header">
                <span>Response</span>
                <span id="status" class="status"></span>
                <span id="time" class="time"></span>
            </div>
            <div class="response-tabs">
                <button class="resp-tab active" onclick="switchResponseTab('body')">Body</button>
                <button class="resp-tab" onclick="switchResponseTab('headers')">Headers</button>
                <button class="resp-tab" onclick="switchResponseTab('cookies')">Cookies</button>
            </div>
            <pre id="response-body"></pre>
        </div>
    </div>
</div>

<!-- Features Section for SEO -->
<section class="features">
    <h2>Powerful Features</h2>
    <div class="feature-grid">
        <div class="feature">
            <h3>All HTTP Methods</h3>
            <p>Support for GET, POST, PUT, DELETE, PATCH, OPTIONS</p>
        </div>
        <div class="feature">
            <h3>Multiple Auth Types</h3>
            <p>Bearer Token, Basic Auth, API Key, OAuth 2.0</p>
        </div>
        <div class="feature">
            <h3>Save Collections</h3>
            <p>Save your APIs locally for quick access</p>
        </div>
        <div class="feature">
            <h3>Import/Export</h3>
            <p>Import Postman collections, export your tests</p>
        </div>
    </div>
</section>

<!-- How-to Section for SEO -->
<section class="how-to">
    <h2>How to Use This API Tester</h2>
    <ol>
        <li>Enter your API endpoint URL</li>
        <li>Select HTTP method</li>
        <li>Add headers or body if needed</li>
        <li>Click Send to test your API</li>
        <li>View response instantly</li>
    </ol>
</section>

<!-- Blog Content for SEO -->
<section class="blog-preview">
    <h2>API Testing Tutorials</h2>
    <div class="articles">
        <article>
            <h3><a href="/blog/how-to-test-rest-api">How to Test REST API: Complete Guide</a></h3>
            <p>Learn the basics of REST API testing with practical examples.</p>
        </article>
        <article>
            <h3><a href="/blog/postman-alternatives">7 Best Postman Alternatives in 2024</a></h3>
            <p>Free and open source tools for API testing.</p>
        </article>
        <article>
            <h3><a href="/blog/api-authentication-methods">API Authentication Methods Explained</a></h3>
            <p>Bearer tokens, OAuth, JWT, and API keys explained.</p>
        </article>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="assets/js/app.js"></script>

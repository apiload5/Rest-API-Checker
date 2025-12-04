class APITester {
    constructor() {
        this.history = JSON.parse(localStorage.getItem('apiHistory') || '[]');
        this.collections = JSON.parse(localStorage.getItem('apiCollections') || '{}');
        this.init();
    }

    init() {
        this.loadHistory();
        this.bindEvents();
        this.updateStats();
    }

    async sendRequest() {
        const method = document.getElementById('method').value;
        const url = document.getElementById('url').value;
        const bodyType = document.getElementById('body-type').value;
        const bodyContent = document.getElementById('body-content').value;

        if (!url) {
            alert('Please enter a URL');
            return;
        }

        // Show loading
        document.getElementById('response-body').textContent = 'Loading...';
        document.getElementById('status').textContent = '';
        document.getElementById('time').textContent = '';

        try {
            const requestData = {
                url: url,
                method: method,
                headers: this.getHeaders(),
                body: this.parseBody(bodyType, bodyContent),
                timeout: 9 // 9 seconds for Vercel timeout
            };

            const startTime = Date.now();
            const response = await fetch('/api/proxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestData)
            });

            const result = await response.json();
            const totalTime = Date.now() - startTime;

            // Display results
            this.displayResponse(result, totalTime);
            
            // Save to history
            this.saveToHistory({
                url,
                method,
                status: result.status,
                time: totalTime,
                timestamp: new Date().toISOString()
            });

        } catch (error) {
            document.getElementById('response-body').textContent = 
                `Error: ${error.message}`;
        }
    }

    parseBody(type, content) {
        if (!content.trim()) return null;
        
        switch(type) {
            case 'json':
                try {
                    JSON.parse(content);
                    return content;
                } catch {
                    return content;
                }
            case 'form':
                // Convert form data to URL encoded
                const formData = new URLSearchParams();
                content.split('\n').forEach(line => {
                    const [key, value] = line.split('=');
                    if (key && value) formData.append(key.trim(), value.trim());
                });
                return formData.toString();
            default:
                return content;
        }
    }

    getHeaders() {
        const headers = {};
        // Get headers from UI
        const headerRows = document.querySelectorAll('.header-row');
        headerRows.forEach(row => {
            const key = row.querySelector('.header-key').value;
            const value = row.querySelector('.header-value').value;
            if (key && value) headers[key] = value;
        });
        return headers;
    }

    displayResponse(result, clientTime) {
        const statusEl = document.getElementById('status');
        const timeEl = document.getElementById('time');
        const bodyEl = document.getElementById('response-body');

        // Status with color
        statusEl.textContent = result.status;
        statusEl.className = 'status ' + 
            (result.status >= 200 && result.status < 300 ? 'success' : 
             result.status >= 400 ? 'error' : 'warning');

        // Time
        timeEl.textContent = `${result.time} (client: ${clientTime}ms)`;

        // Body with formatting
        try {
            const parsed = JSON.parse(result.body);
            bodyEl.textContent = JSON.stringify(parsed, null, 2);
        } catch {
            bodyEl.textContent = result.body;
        }

        // Syntax highlighting
        this.highlightJSON(bodyEl);
    }

    highlightJSON(element) {
        element.innerHTML = element.textContent
            .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
            match => {
                let cls = 'number';
                if (/^"/.test(match)) {
                    cls = /:$/.test(match) ? 'key' : 'string';
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return `<span class="${cls}">${match}</span>`;
            });
    }

    saveToHistory(request) {
        this.history.unshift(request);
        if (this.history.length > 50) this.history.pop();
        localStorage.setItem('apiHistory', JSON.stringify(this.history));
        this.loadHistory();
    }

    loadHistory() {
        const historyEl = document.getElementById('history-list');
        if (!historyEl) return;

        historyEl.innerHTML = this.history.slice(0, 10)
            .map(item => `
                <div class="history-item" onclick="loadFromHistory('${item.url}', '${item.method}')">
                    <span class="method ${item.method}">${item.method}</span>
                    <span class="url">${item.url}</span>
                    <span class="status ${item.status >= 200 && item.status < 300 ? 'success' : 'error'}">
                        ${item.status}
                    </span>
                    <span class="time">${item.time}ms</span>
                </div>
            `).join('');
    }

    updateStats() {
        // Update counters for SEO
        const totalRequests = this.history.length;
        document.getElementById('total-requests').textContent = totalRequests;
    }
}

// Initialize when page loads
let apiTester;
document.addEventListener('DOMContentLoaded', () => {
    apiTester = new APITester();
    
    // Make functions globally available
    window.sendRequest = () => apiTester.sendRequest();
    window.switchTab = (tabName) => apiTester.switchTab(tabName);
});

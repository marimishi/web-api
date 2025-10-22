const limitSelect = document.getElementById('limitSelect');
const refreshBtn = document.getElementById('refreshBtn');
const testBtn = document.getElementById('testBtn');
const loading = document.getElementById('loading');
const error = document.getElementById('error');
const results = document.getElementById('results');
const stats = document.getElementById('stats');

document.addEventListener('DOMContentLoaded', function() {
    loadData();
});

limitSelect.addEventListener('change', function() {
    loadData();
});

refreshBtn.addEventListener('click', function() {
    loadData();
});

testBtn.addEventListener('click', function() {
    testAPI();
});

async function loadData() {
    const limit = limitSelect.value;

    loading.style.display = 'block';
    error.style.display = 'none';
    results.innerHTML = '';
    stats.style.display = 'none';

    try {
        const response = await fetch(`/api-test-simple.php?limit=${limit}`);
        const data = await response.json();

        loading.style.display = 'none';
        
        if (data.status === 'success') {
            displayStats(data.data);
            displayResults(data.data);
            stats.style.display = 'grid';
        } else {
            showError(data.message || 'Произошла ошибка в API');
        }

    } catch (err) {
        loading.style.display = 'none';
        showError('Ошибка подключения: ' + err.message);
    }
}

function displayStats(data) {
    document.getElementById('totalElements').textContent = data.total_count;
    
    const inStockCount = data.items.filter(item => item.properties.in_stock).length;
    document.getElementById('inStock').textContent = inStockCount;
    
    const prices = data.items.map(item => item.properties.price).filter(price => price !== null);
    const avgPrice = prices.length > 0 ? Math.round(prices.reduce((a, b) => a + b) / prices.length) : 0;
    document.getElementById('avgPrice').textContent = avgPrice + ' ₽';
    
    document.getElementById('iblock-info').textContent = `${data.iblock_info.name} (ID: ${data.iblock_info.id})`;
}

function displayResults(data) {
    if (!data.items || data.items.length === 0) {
        results.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <div style="font-size: 4rem; color: #bdc3c7; margin-bottom: 20px;">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 style="color: #7f8c8d; margin-bottom: 10px;">Элементы не найдены</h3>
                <p style="color: #95a5a6;">Создайте тестовые элементы в инфоблоке</p>
            </div>
        `;
        return;
    }

    let html = '';
    
    data.items.forEach(item => {
        const inStockBadge = item.properties.in_stock ? 
            '<span class="badge badge-success"><i class="fas fa-check"></i> В наличии</span>' :
            '<span class="badge badge-danger"><i class="fas fa-times"></i> Нет в наличии</span>';

        html += `
            <div class="element-card">
                <div class="element-header">
                    <div>
                        <h3 style="margin: 0; color: white;">
                            <i class="fas fa-cube"></i> ${item.name || 'Без названия'}
                        </h3>
                        <div style="margin-top: 5px; color: #ecf0f1;">
                            <i class="fas fa-hashtag"></i> ID: ${item.id}
                            ${item.date ? ` | <i class="fas fa-calendar"></i> ${item.date}` : ''}
                        </div>
                    </div>
                    <div>
                        ${inStockBadge}
                    </div>
                </div>
                
                <div class="element-body">
                    ${item.preview_text ? `
                        <div style="background: #e8f4fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3498db;">
                            <strong><i class="fas fa-align-left"></i> Описание:</strong>
                            <p style="margin: 10px 0 0 0; color: #2c3e50;">${item.preview_text}</p>
                        </div>
                    ` : ''}
                    
                    <h4 style="color: #2c3e50; margin-bottom: 15px;">
                        <i class="fas fa-list-alt"></i> Свойства элемента
                    </h4>
                    
                    <div class="property-grid">
                        <div class="property-item">
                            <div class="property-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div>
                                <strong>Цена</strong><br>
                                <span style="color: #27ae60; font-weight: 600;">
                                    ${item.properties.price ? item.properties.price + ' ₽' : 'Не указана'}
                                </span>
                            </div>
                        </div>
                        
                        <div class="property-item">
                            <div class="property-icon" style="background: #e74c3c;">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div>
                                <strong>Цвет</strong><br>
                                ${item.properties.color || 'Не указан'}
                            </div>
                        </div>
                        
                        <div class="property-item">
                            <div class="property-icon" style="background: #f39c12;">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <strong>Дата изготовления</strong><br>
                                ${item.properties.manufacture_date || 'Не указана'}
                            </div>
                        </div>
                    </div>
                    
                    ${item.properties.description ? `
                        <div style="margin-top: 20px;">
                            <div class="property-item" style="background: #fff3cd; border-left: 4px solid #ffc107;">
                                <div class="property-icon" style="background: #f39c12;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div style="flex-grow: 1;">
                                    <strong>Детальное описание</strong><br>
                                    <p style="margin: 8px 0 0 0; color: #856404;">${item.properties.description}</p>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    });

    results.innerHTML = html;
}

function showError(message) {
    const errorMessage = document.getElementById('error-message');
    
    errorMessage.textContent = message;
    error.style.display = 'block';
}

function testAPI() {
    window.open('/api-test-simple.php?limit=3', '_blank');
}
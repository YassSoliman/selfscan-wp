/**
 * Blog functionality - Load More Articles
 */

document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-articles');
    
    if (!loadMoreBtn) {
        return; // Exit if button not found (not on blog page)
    }
    
    const articlesContent = document.querySelector('.articles-blog-body__content');
    
    if (!articlesContent) {
        return; // Exit if articles container not found
    }
    
    loadMoreBtn.addEventListener('click', function() {
        const offset = parseInt(this.dataset.offset, 10);
        const button = this;
        const buttonText = button.querySelector('.button__text');
        const originalText = buttonText.textContent;
        
        // Disable button and show loading state
        button.disabled = true;
        buttonText.textContent = 'Loading...';
        button.style.opacity = '0.6';
        
        // Prepare AJAX data
        const formData = new FormData();
        formData.append('action', 'load_more_articles');
        formData.append('offset', offset);
        formData.append('nonce', wp_ajax_object.nonce);
        
        // Make AJAX request
        fetch(wp_ajax_object.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html.trim() !== '') {
                // Append new articles with smooth animation
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.data.html;
                
                // Add each article with a slight delay for smooth loading effect
                const newArticles = tempDiv.querySelectorAll('.articles-blog-body__article');
                newArticles.forEach((article, index) => {
                    setTimeout(() => {
                        article.style.opacity = '0';
                        article.style.transform = 'translateY(20px)';
                        articlesContent.appendChild(article);
                        
                        // Animate in
                        setTimeout(() => {
                            article.style.transition = 'all 0.3s ease';
                            article.style.opacity = '1';
                            article.style.transform = 'translateY(0)';
                        }, 10);
                    }, index * 100);
                });
                
                // Update button offset
                button.dataset.offset = data.data.new_offset;
                
                // Hide button if no more posts
                if (!data.data.has_more) {
                    button.style.display = 'none';
                }
            } else {
                // No more articles
                button.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading more articles:', error);
            buttonText.textContent = 'Error loading articles';
            
            // Reset button after error
            setTimeout(() => {
                button.disabled = false;
                buttonText.textContent = originalText;
                button.style.opacity = '1';
            }, 2000);
        })
        .finally(() => {
            // Reset button state if still visible
            if (button.style.display !== 'none') {
                button.disabled = false;
                buttonText.textContent = originalText;
                button.style.opacity = '1';
            }
        });
    });
});
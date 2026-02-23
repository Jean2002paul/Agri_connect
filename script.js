// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.navbar-nav a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form submission handler
    const contactForm = document.querySelector('.contact-form form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            
            // Simple validation
            const name = this.querySelector('input[placeholder="Votre nom"]').value;
            const surname = this.querySelector('input[placeholder="Vos prénoms"]').value;
            const email = this.querySelector('input[type="email"]').value;
            const subject = this.querySelector('input[placeholder="Sujet"]').value;
            const message = this.querySelector('textarea').value;
            
            if (!name || !surname || !email || !subject || !message) {
                showAlert('Veuillez remplir tous les champs du formulaire.', 'warning');
                return;
            }
            
            if (!validateEmail(email)) {
                showAlert('Veuillez entrer une adresse email valide.', 'warning');
                return;
            }
            
            // Simulate form submission
            showAlert('Message envoyé avec succès! Nous vous répondrons dans les plus brefs délais.', 'success');
            this.reset();
        });
    }

    // Product item click handlers
    const productItems = document.querySelectorAll('.product-item');
    productItems.forEach(item => {
        item.addEventListener('click', function() {
            const productName = this.querySelector('p').textContent;
            const categoryName = this.closest('.filiere-card').querySelector('h3').textContent;
            showProductDetails(productName, categoryName);
        });
    });

    // Hero button interactions
    const buyButton = document.querySelector('.btn-primary-custom');
    const sellButton = document.querySelector('.btn-outline-custom');
    
    if (buyButton) {
        buyButton.addEventListener('click', function() {
            showAlert('Redirection vers la marketplace...', 'info');
            // In a real application, this would redirect to the marketplace
            setTimeout(() => {
                console.log('Navigate to marketplace');
            }, 1500);
        });
    }
    
    if (sellButton) {
        sellButton.addEventListener('click', function() {
            showAlert('Redirection vers l\'espace vendeur...', 'info');
            // In a real application, this would redirect to seller space
            setTimeout(() => {
                console.log('Navigate to seller space');
            }, 1500);
        });
    }

    // Partner button interactions
    const partnerButtons = document.querySelectorAll('.partner-btn');
    partnerButtons.forEach(button => {
        button.addEventListener('click', function() {
            const partnerName = this.textContent;
            showAlert(`En savoir plus sur notre partenaire ${partnerName}`, 'info');
        });
    });

    // Social media links
    const socialLinks = document.querySelectorAll('.social-icons a');
    socialLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.querySelector('i').className.split(' ')[1].replace('fa-', '');
            showAlert(`Ouverture de ${platform} dans une nouvelle fenêtre...`, 'info');
        });
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.padding = '0.5rem 0';
            navbar.style.transition = 'padding 0.3s ease';
        } else {
            navbar.style.padding = '1rem 0';
        }
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.filiere-card, .contact-form, .contact-info');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});

// Utility functions
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlert = document.querySelector('.custom-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `custom-alert alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${getAlertIcon(type)} me-2"></i>
            ${message}
        </div>
    `;

    // Add to page
    document.body.appendChild(alertDiv);

    // Auto remove after 3 seconds
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 300);
    }, 3000);
}

function getAlertIcon(type) {
    const icons = {
        'success': 'check-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle',
        'danger': 'times-circle'
    };
    return icons[type] || 'info-circle';
}

function showProductDetails(productName, categoryName) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${productName}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Catégorie:</strong> ${categoryName}</p>
                    <p>Découvrez nos produits de qualité supérieure dans la catégorie ${categoryName.toLowerCase()}.</p>
                    <p>Le ${productName} est l'un de nos produits phares, cultivé avec soin par nos coopératives locales.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">En savoir plus</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

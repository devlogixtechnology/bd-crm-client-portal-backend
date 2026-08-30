// Toast Notification System
function showToast(message, type = 'success') {
    const container = document.querySelector('.toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

function createToastContainer() {
    const div = document.createElement('div');
    div.className = 'toast-container';
    document.body.appendChild(div);
    return div;
}

// SUBTASK 1: Project Timeline (Updated & Safe)
async function loadTimeline() {
    const container = document.getElementById('timeline-container');
    if (!container) return;

    // Loading state
    container.innerHTML = '<div class="loading-spinner"><i class="fas fa-circle-notch fa-spin"></i> Loading timeline...</div>';

    try {
        const response = await fetch('../api/client/project-timeline.php');
        const data = await response.json();

        if (!data.success || !data.project) {
            container.innerHTML = `<div class="card"><p class="empty-state"><i class="fas fa-folder-open"></i> No active project found for your account.</p></div>`;
            return;
        }

        const project = data.project;
        const timeline = data.timeline || [];

        let timelineHTML = `
            <div class="card">
                <div class="progress-container">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                        <h3>${escapeHTML(project.name)}</h3>
                        <span class="badge badge-${project.status.toLowerCase().replace(' ', '_')}">${project.status}</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: ${project.progress}%"></div>
                    </div>
                    <p style="text-align:right; font-size:0.875rem; color:var(--text-muted); margin-top:0.5rem;">${project.progress}% Complete</p>
                </div>
                <div class="timeline">
        `;

        if (timeline.length === 0) {
            timelineHTML += `<p class="empty-state">No timeline events available yet.</p>`;
        } else {
            timeline.forEach(item => {
                timelineHTML += `
                    <div class="timeline-item ${item.status}">
                        <h4>${escapeHTML(item.title)}</h4>
                        <p>${escapeHTML(item.description || '')} <br><small>${item.date || ''}</small></p>
                    </div>
                `;
            });
        }
        timelineHTML += `</div></div>`;
        container.innerHTML = timelineHTML;
    } catch (error) {
        container.innerHTML = `<div class="card"><p class="empty-state">Failed to load timeline. Please try again later.</p></div>`;
    }
}

// SUBTASK 2: Client Invoices (Updated & Safe)
async function loadInvoices() {
    const container = document.getElementById('invoices-container');
    if (!container) return;

    // Loading state
    container.innerHTML = '<div class="loading-spinner"><i class="fas fa-circle-notch fa-spin"></i> Loading invoices...</div>';

    try {
        const response = await fetch('../api/client/invoices.php');
        const data = await response.json();

        if (!data.success) {
            container.innerHTML = `<div class="card"><p class="empty-state">Error fetching invoices.</p></div>`;
            return;
        }

        const invoices = data.invoices || [];

        if (invoices.length === 0) {
            container.innerHTML = `<div class="card"><p class="empty-state"><i class="fas fa-file-invoice"></i> No invoices found.</p></div>`;
            return;
        }

        let tableHTML = `
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        invoices.forEach(inv => {
            const statusClass = inv.status.toLowerCase();
            tableHTML += `
                <tr>
                    <td><strong>${escapeHTML(inv.invoice_number)}</strong></td>
                    <td>${inv.date}</td>
                    <td>${inv.due_date}</td>
                    <td>$${parseFloat(inv.amount).toFixed(2)}</td>
                    <td><span class="badge badge-${statusClass}">${inv.status}</span></td>
                    <td>
                        <a href="${escapeHTML(inv.download_url)}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
            `;
        });

        tableHTML += `</tbody></table></div></div>`;
        container.innerHTML = tableHTML;
    } catch (error) {
        container.innerHTML = `<div class="card"><p class="empty-state">Failed to load invoices.</p></div>`;
    }
}

// SUBTASK 3: Agreement Digital Signature
async function handleAgreementSign(e, form) {
    e.preventDefault();
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    const checkbox = form.querySelector('#confirm-terms');
    if (!checkbox.checked) {
        showToast('You must confirm that you agree to the terms.', 'error');
        return;
    }

    const agreementId = form.dataset.agreementId;
    const signature = form.querySelector('input[name="signature"]').value.trim();

    if (!signature) {
        showToast('Please enter your signature.', 'error');
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Signing...';

    try {
        const response = await fetch('../api/client/agreement-sign.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ agreement_id: parseInt(agreementId), signature: signature })
        });
        const data = await response.json();

        if (!data.success) throw new Error(data.message);

        showToast(data.message, 'success');
        
        const badge = document.getElementById(`status-badge-${agreementId}`);
        if (badge) {
            badge.textContent = 'Signed';
            badge.className = 'badge badge-completed';
        }
        form.innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle"></i> This agreement has been successfully signed.</div>`;
        
    } catch (error) {
        showToast(error.message || 'Failed to sign agreement.', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Utility: Escape HTML
function escapeHTML(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
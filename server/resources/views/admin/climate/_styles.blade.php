<style>
    /* Climate Content Specific Styles */
    .climate-card {
        transition: all 0.3s ease;
    }

    .climate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stat-card {
        border-left: 4px solid #0F3E22;
    }

    .image-preview-container {
        position: relative;
        display: inline-block;
    }

    .image-preview-container img {
        border: 2px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .badge-status {
        font-size: 0.875rem;
        padding: 0.35em 0.65em;
    }

    /* Make tables responsive */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
        }

        .btn-group .btn {
            margin-bottom: 5px;
        }
    }

    /* Form enhancements */
    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0F3E22;
        box-shadow: 0 0 0 0.2rem rgba(15, 62, 34, 0.25);
    }

    /* Card header styling */
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #0F3E22;
    }

    /* Alert improvements */
    .alert {
        border-radius: 0.5rem;
        border-left: 4px solid;
    }

    .alert-success {
        border-left-color: #28a745;
    }

    .alert-danger {
        border-left-color: #dc3545;
    }
</style>

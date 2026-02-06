<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body * {
            visibility: hidden;
        }

        #print-area,
        #print-area * {
            visibility: visible;
        }

        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
            border: none !important;
        }

        .print-page {
            page-break-after: always;
            padding: 10mm !important;
        }

        .print-page:last-child {
            page-break-after: auto;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }

    @media screen {
        .print-page {
            border-bottom: 2px dashed #ccc;
            margin-bottom: 20px;
        }

        .print-page:last-child {
            border-bottom: none;
        }
    }
</style>

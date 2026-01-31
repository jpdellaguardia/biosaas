# clinical_cases.py

CLINICAL_CASES = {
    "DEMO_SICKLE_CELL": {
        "patient_id": "Paciente A",
        "name": "Severino",
        "condition": "Anemia Falciforme",
        "gene": "HBB",
        "variants": [
            {
                "rsid": "rs334",
                "chrom": "11",
                "pos": 5227002,
                "ref": "A",
                "alt": "T",
                "effect": "p.Glu6Val",
                "clinical_significance": "Pathogenic"
            }
        ],
        "ancestry": ["Africana", "Indígena"],
        "drug_contraindications": [
            "Hidroxiureia sem monitoramento",
            "Desidratação severa",
            "Altas doses de AINEs"
        ],
        "teaching_question": "Qual medicamento ou condição este paciente deve evitar?"
    },

    "DEMO_THROMBOSIS": {
        "patient_id": "Paciente B",
        "name": "Maria",
        "condition": "Risco de Trombose",
        "gene": "F5",
        "variants": [
            {
                "rsid": "rs6025",
                "chrom": "1",
                "pos": 169519049,
                "ref": "G",
                "alt": "A",
                "effect": "Factor V Leiden",
                "clinical_significance": "Pathogenic"
            }
        ],
        "pharmacogenomics": {
            "drug": "Varfarina",
            "risk": "Alta sensibilidade",
            "recommendation": "Reduzir dose inicial"
        },
        "teaching_question": "Por que este paciente precisa de ajuste de dose?"
    }
}
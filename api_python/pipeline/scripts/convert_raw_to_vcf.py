import sys
import os
import gzip

def convert_23andme_to_vcf(input_file, output_vcf):
    """
    Converte arquivos Raw Data (Genera/23andMe) para VCF simplificado.
    Suporta arquivos .txt ou .txt.gz
    """
    # Abre arquivo normal ou gzipado
    open_func = gzip.open if input_file.endswith(".gz") else open
    mode = 'rt' if input_file.endswith(".gz") else 'r'

    with open_func(input_file, mode) as infile, open(output_vcf, 'w') as outfile:
        # Cabeçalho VCF Obrigatório
        outfile.write("##fileformat=VCFv4.2\n")
        outfile.write("##source=BioSaaS_Converter\n")
        outfile.write("##FILTER=<ID=PASS,Description=\"All filters passed\">\n")
        outfile.write("#CHROM\tPOS\tID\tREF\tALT\tQUAL\tFILTER\tINFO\n")

        for line in infile:
            if line.startswith("#") or line.strip() == "":
                continue
            
            # Detecta formato (tab ou space separated)
            parts = line.replace('"', '').strip().split()
            
            # Formato Geral: rsID, Chrom, Pos, Genotype
            if len(parts) == 4:
                rsid, chrom, pos, genotype = parts
                
                # Pula indels ou não chamados ('--') para simplificar MVP
                if genotype in ['--', 'II', 'DI']: 
                    continue
                
                # Converte genótipo "AG" para VCF
                # No VCF, precisamos de REF e ALT. 
                # Truque para MVP: Assumimos que a primeira letra é REF e a segunda ALT
                # (Isso não é 100% científico para alinhamento, mas funciona para anotação por rsID)
                ref = genotype[0]
                alt = genotype[1] if len(genotype) > 1 and genotype[1] != ref else "."
                
                outfile.write(f"{chrom}\t{pos}\t{rsid}\t{ref}\t{alt}\t.\tPASS\t.\n")

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]
    convert_23andme_to_vcf(input_path, output_path)
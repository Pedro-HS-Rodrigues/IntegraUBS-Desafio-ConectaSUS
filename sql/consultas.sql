-- Listar os pacientes com seus nomes e o nome da respectiva unidade de saúde(JOIN).
SELECT
    p.id,
    p.name AS patient_name,
    p.cpf,
    p.birth_date,
    p.phone,
    h.name AS health_unit_name
FROM patients AS p
INNER JOIN health_units AS h ON h.id = p.health_unit_id
ORDER BY p.name;


-- Contar quantos pacientes existem em cada unidade, incluindo unidades sem pacientes (GROUP BY e LEFT JOIN).
SELECT
    h.id,
    h.name AS health_unit_name,
    COUNT(p.id) AS total_patients
FROM health_units AS h
LEFT JOIN patients AS p ON p.health_unit_id = h.id
GROUP BY h.id, h.name
ORDER BY total_patients DESC, h.name;


-- Listar os pacientes cujo nome contenha um termo informado, como “Maria”(LIKE).
SELECT
    p.id,
    p.name AS patient_name,
    p.cpf,
    h.name AS health_unit_name
FROM patients AS p
INNER JOIN health_units AS h ON h.id = p.health_unit_id
WHERE p.name LIKE '%Maria%'
ORDER BY p.name;

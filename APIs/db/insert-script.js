function createInsertStatement(jsonData) {
  let insertStatement = "INSERT INTO drug_information (\n";
  insertStatement += "  name,\n";
  insertStatement += "  size,\n";
  insertStatement += "  use_medicine,\n";
  insertStatement += "  contraindications,\n";
  insertStatement += "  properties,\n";
  insertStatement += "  drug_type_id,\n";
  insertStatement += "  category_id,\n";
  insertStatement += "  package_id,\n";
  insertStatement += "  quantity,\n";
  insertStatement += "  production_date,\n";
  insertStatement += "  expiration_date,\n";
  insertStatement += "  price,\n";
  insertStatement += "  keyword,\n";
  insertStatement += "  link_images\n";
  insertStatement += ")\n";
  insertStatement += "VALUES\n";

  for (let i = 0; i < jsonData.length; i++) {
    const data = jsonData[i];

    insertStatement += `  (\n`;
    insertStatement += `    '${data.name}',\n`;
    insertStatement += `    '${data.size}',\n`;
    insertStatement += `    '${data.use_medicine}',\n`;
    insertStatement += `    ${data.contraindications ? `'${data.contraindications}',\n` : 'NULL,\n'}`;
    insertStatement += `    '${data.properties}',\n`;
    insertStatement += `    ${data.drug_type_id || null},\n`;
    insertStatement += `    ${data.category_id || null},\n`;
    insertStatement += `    ${data.package_id || null},\n`;
    insertStatement += `    ${data.quantity || null},\n`;
    insertStatement += `    '',\n`;
    insertStatement += `    '',\n`;
    insertStatement += `    ${data.price || null},\n`;
    insertStatement += `    '${data.keyword}',\n`;
    insertStatement += `    ${data.link_images ? `'${data.link_images}'` : 'NULL'}\n`;
    insertStatement += `  )${i < jsonData.length - 1 ? ',' : ''}\n`;
  }

  return insertStatement;
}

const sqlStatement = createInsertStatement(arr);
console.log(sqlStatement);
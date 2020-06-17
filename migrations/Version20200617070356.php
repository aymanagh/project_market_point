<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617070356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_production_line (product_id INT NOT NULL, production_line_id INT NOT NULL, INDEX IDX_388FFA7F4584665A (product_id), INDEX IDX_388FFA7F586EF89F (production_line_id), PRIMARY KEY(product_id, production_line_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_production_line ADD CONSTRAINT FK_388FFA7F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_production_line ADD CONSTRAINT FK_388FFA7F586EF89F FOREIGN KEY (production_line_id) REFERENCES production_line (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD586EF89F');
        $this->addSql('DROP INDEX IDX_D34A04AD586EF89F ON product');
        $this->addSql('ALTER TABLE product DROP production_line_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_production_line');
        $this->addSql('ALTER TABLE product ADD production_line_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD586EF89F FOREIGN KEY (production_line_id) REFERENCES production_line (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD586EF89F ON product (production_line_id)');
    }
}

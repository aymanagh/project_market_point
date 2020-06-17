<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616134854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9CA26EF2');
        $this->addSql('DROP INDEX IDX_D34A04AD9CA26EF2 ON product');
        $this->addSql('ALTER TABLE product CHANGE product_line_id production_line_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD586EF89F FOREIGN KEY (production_line_id) REFERENCES production_line (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD586EF89F ON product (production_line_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD586EF89F');
        $this->addSql('DROP INDEX IDX_D34A04AD586EF89F ON product');
        $this->addSql('ALTER TABLE product CHANGE production_line_id product_line_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9CA26EF2 FOREIGN KEY (product_line_id) REFERENCES production_line (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD9CA26EF2 ON product (product_line_id)');
    }
}

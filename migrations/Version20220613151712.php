<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613151712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grupo ADD autor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE grupo ADD CONSTRAINT FK_8C0E9BD314D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C0E9BD314D45BBE ON grupo (autor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grupo DROP FOREIGN KEY FK_8C0E9BD314D45BBE');
        $this->addSql('DROP INDEX UNIQ_8C0E9BD314D45BBE ON grupo');
        $this->addSql('ALTER TABLE grupo DROP autor_id');
    }
}
